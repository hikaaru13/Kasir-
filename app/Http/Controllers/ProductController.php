<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Method index untuk menampilkan halaman utama
    public function index()
    {
        $product = product::all();
        return view('content.product.index', compact('product'));
    }

    // Method untuk menyimpan data baru atau update data
    public function saveproduct(Request $request)
    {
        // Ambil hanya kolom yang ada dalam array $columns
        $data = $request->only([
            'product_id',
            'name',
            'stock',
            'price',
            'variant',
            'category',        ]);

        $itemId = $request->input('product_id');
        $isUpdate = !empty($itemId);

        if ($isUpdate) {
            $item = product::find($itemId);
            if (!$item) {
                return redirect()->back()->withErrors(['error' => 'Data not found.']);
            }
        } else {
            $item = new product();
        }

        $item->fill($data);

        if ($item->save()) {
            return redirect()->route('product.index')->with('success', 'Data saved successfully.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to save data.']);
        }
    }

    // Method untuk menghapus data
    public function deleteproduct($id)
    {
        $item = product::find($id);
        if ($item && $item->delete()) {
            return redirect()->route('product.index')->with('success', 'Data deleted successfully.');
        }
        return redirect()->back()->withErrors(['error' => 'Failed to delete data.']);
    }
}