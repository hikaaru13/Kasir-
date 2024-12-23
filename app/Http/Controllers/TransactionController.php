<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('product')->get();
        $products = Product::all();
        return view('content.transaction.index', compact('transactions', 'products'));
    }
    
    public function savetransaction(Request $request)
    {
        $data = $request->only([
            'customer_name',
            'qty',
            'method_payment',
            'total',
            'product_id',
        ]);

        $product = Product::find($data['product_id']);

        if (!$product || $product->stock < $data['qty']) {
            return redirect()->back()->with('error', 'Stok produk ini tidak mencukupi.');
        }

        $transactionId = $request->input('id');
        $transaction = $transactionId ? Transaction::find($transactionId) : new Transaction();

        if (!$transaction) {
            return redirect()->back()->with('error', 'Data not found.');
        }

        try {
            DB::beginTransaction();

            if (!$transaction->exists) {
                // Reduce product stock
                $product->stock -= $data['qty'];
                $product->save();
            } else {
                $oldQty = $transaction->qty ?? 0;
                $stockDifference = $data['qty'] - $oldQty;

                if ($stockDifference != 0) {
                    $product->stock -= $stockDifference;
                    $product->save();
                }
            }

            $transaction->fill($data);

            $currentTimestamp = now()->setTimezone('Asia/Jakarta');

            if (!$transaction->exists) {
                $transaction->created_at = $currentTimestamp;
            }

            $transaction->updated_at = $currentTimestamp;

            if ($transaction->save()) {
                DB::commit();
                return redirect()->route('transaction.index')->with('success', 'Data saved successfully.');
            }

            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save data.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function deletetransaction($id)
    {
        $transaction = Transaction::find($id);

        if ($transaction && $transaction->delete()) {
            return redirect()->route('transaction.index')->with('success', 'Data deleted successfully.');
        }

        return redirect()->back()->withErrors(['error' => 'Failed to delete data.']);
    }

    public function exportToExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $methodPayment = $request->input('method_payment');
        $productId = $request->input('product_id');

        $filename = 'transactions_export_' . 
            ($startDate ? $startDate . '_' : '') . 
            ($endDate ? $endDate . '_' : '') . 
            ($methodPayment ? $methodPayment . '_' : '') . 
            date('YmdHis') . '.xlsx';

        return Excel::download(
            new TransactionsExport($startDate, $endDate, $methodPayment, $productId), 
            $filename
        );
    }
}
