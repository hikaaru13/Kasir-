<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'monthly');
        
        $totalRevenue = $this->getTotalRevenue($filter);
        $dailyRevenue = $this->getDailyRevenue($filter);
        $monthlyRevenue = $this->getMonthlyRevenue($filter);
        $yearlyRevenue = $this->getYearlyRevenue($filter);
        
        $productSales = $this->getProductSales($filter);
        $productRankings = $this->getProductRankings($filter);

        $previousTotalRevenue = $this->getTotalRevenue($filter, true);
        $previousYearlyRevenue = $this->getYearlyRevenue($filter, true);

        return view('content.laporan.index', compact(
            'totalRevenue', 
            'dailyRevenue', 
            'monthlyRevenue', 
            'yearlyRevenue', 
            'productSales',
            'productRankings', 
            'filter',
            'previousTotalRevenue',
            'previousYearlyRevenue'
        ));
    }

    private function getTotalRevenue($filter, $previous = false)
    {
        $query = Transaction::query();
        $query = $this->applyTimeFilter($query, $filter, $previous);
        return $query->sum('total');
    }

    private function getDailyRevenue($filter, $previous = false)
    {
        $query = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total_revenue'),
            DB::raw('SUM(qty) as total_quantity')
        );
        $query = $this->applyTimeFilter($query, $filter, $previous);
        return $query->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get();
    }

    private function getMonthlyRevenue($filter, $previous = false)
    {
        $query = Transaction::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total_revenue'),
            DB::raw('SUM(qty) as total_quantity')
        );
        $query = $this->applyTimeFilter($query, $filter, $previous);
        return $query->groupBy('year', 'month')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
    }

    private function getYearlyRevenue($filter, $previous = false)
    {
        $query = Transaction::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total) as total_revenue'),
            DB::raw('SUM(qty) as total_quantity')
        );
        $query = $this->applyTimeFilter($query, $filter, $previous);
        return $query->groupBy('year')
            ->orderBy('year', 'DESC')
            ->get();
    }

    private function getProductSales($filter, $previous = false)
    {
        $query = Transaction::select(
            'products.name',
            DB::raw('SUM(transactions.qty) as total_quantity'),
            DB::raw('SUM(transactions.total) as total_revenue')
        )
        ->join('products', 'transactions.product_id', '=', 'products.product_id');
        
        $query = $this->applyTimeFilter($query, $filter, $previous);
        
        return $query->groupBy('products.name')
            ->orderBy('total_revenue', 'DESC')
            ->get();
    }

    private function getProductRankings($filter, $previous = false)
    {
        $query = Transaction::select(
            'products.name',
            DB::raw('SUM(transactions.qty) as total_quantity'),
            DB::raw('SUM(transactions.total) as total_revenue')
        )
        ->join('products', 'transactions.product_id', '=', 'products.product_id');
        
        $query = $this->applyTimeFilter($query, $filter, $previous);
        
        return $query->groupBy('products.name')
            ->orderBy('total_quantity', 'DESC')
            ->limit(10)
            ->get();
    }

    private function applyTimeFilter($query, $filter, $previous = false)
    {
        $now = Carbon::now();

        switch ($filter) {
            case 'all':
                return $query;

            case 'weekly':
                $startDate = $previous ? $now->copy()->subWeek()->startOfWeek() : $now->copy()->startOfWeek();
                $endDate = $previous ? $now->copy()->subWeek()->endOfWeek() : $now->copy()->endOfWeek();
                return $query->whereBetween('transactions.created_at', [$startDate, $endDate]);

            case 'yearly':
                $year = $previous ? $now->copy()->subYear()->year : $now->copy()->year;
                return $query->whereYear('transactions.created_at', $year);

            case 'monthly':
            default:
                $year = $previous ? $now->copy()->subYear()->year : $now->copy()->year;
                $month = $previous ? $now->copy()->subYear()->month : $now->copy()->month;
                return $query->whereYear('transactions.created_at', $year)
                             ->whereMonth('transactions.created_at', $month);
        }
    }
}