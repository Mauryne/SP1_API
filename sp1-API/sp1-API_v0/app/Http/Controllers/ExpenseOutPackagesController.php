<?php

namespace App\Http\Controllers;

use App\ExpenseOutPackages;
use Illuminate\Http\Request;

class ExpenseOutPackagesController extends Controller
{
    public function getAll()
    {
        $expenses = ExpenseOutPackages::with('expense', 'expenseProof')->get();
        return $expenses;
    }
}
