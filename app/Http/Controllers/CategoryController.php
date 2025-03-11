<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $userCategories = Auth::user()->categories;
        return view('categories.index', ['categories' => $userCategories]);
    }

    public function show($categoryId)
    {
        if (!auth()->user()->categories->contains($categoryId)) {
            abort(403);
        }

        $category = Category::find($categoryId);

        if (!$category) {
            abort(404);
        }

        $currentMonth = now()->endOfMonth();
        $lastYearDate = $currentMonth->copy()->subMonths(12);

        $expenses = Expense::where('category_id', $categoryId)
            ->whereBetween('expense_date', [$lastYearDate, $currentMonth]) 
            ->selectRaw('
                strftime("%Y", expense_date) as year,
                strftime("%m", expense_date) as month,
                SUM(amount) as total_expense')
            ->groupBy(DB::raw('strftime("%Y", expense_date), strftime("%m", expense_date)'))
            ->orderByDesc(DB::raw('strftime("%Y", expense_date), strftime("%m", expense_date)'))
            ->get();

        $currentBudget = $category->budget;

        $pastSixMonths = [];

        $months = collect(range(0, 5))->map(function ($i) use ($currentMonth) {
            return $currentMonth->copy()->subMonths($i)->format('Y-m'); 
        });

        foreach ($months as $month) {
            $pastSixMonths[$month] = 0;
        }

        foreach ($expenses as $expense) {
            $monthKey = "{$expense->year}-" . str_pad($expense->month, 2, '0', STR_PAD_LEFT); 
            $pastSixMonths[$monthKey] = $expense->total_expense;
        }

        ksort($pastSixMonths);

        $data = [];

        $monthNames = [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Okt',
            '11' => 'Nov',
            '12' => 'Dec'
        ];

        $data['Spending'] = [
            'name' => 'Spending',
            'type' => 'column',
            'color' => '#1A56DB',
            'data' => []
        ];

        $data['Budget'] = [
            'name' => 'Budget',
            'type' => 'line',
            'color' => '#34D399',
            'data' => []
        ];

        foreach ($pastSixMonths as $month => $expense) {
            $monthNum = substr($month, 5, 2);
            $monthName = $monthNames[$monthNum];

            $data['Spending']['data'][] = ['x' => $monthName, 'y' => $expense];

            $data['Budget']['data'][] = ['x' => $monthName, 'y' => $currentBudget];
        }

        return view('categories.show', [
            'category' => $category,
            'data' => $data
        ]);
    }
}
