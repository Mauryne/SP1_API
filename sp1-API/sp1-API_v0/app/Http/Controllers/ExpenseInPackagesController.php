<?php

namespace App\Http\Controllers;

use App\ExpenseInPackages;
use Illuminate\Http\Request;

class ExpenseInPackagesController extends Controller
{
    public function getAll()
    {
        $expenses = ExpenseInPackages::with('expense', 'expensePackage')->get();
        return $expenses;
    }
}
