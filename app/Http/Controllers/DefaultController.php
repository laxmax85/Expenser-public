<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DefaultController extends Controller
{
    public function about()
    {
        return view('about.show');
    }

    public function dashboard()
    {
        $userCategories = Auth::user()->categories;

        return view('dashboard', [
            'categories' => $userCategories,
        ]);
    }
}
