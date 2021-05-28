<?php

namespace App\Http\Controllers;

use App\ExpenseOutPackages;
use Illuminate\Http\Request;

class ExpenseOutPackagesController extends Controller
{
    public function getAll()
    {
        $expenses = ExpenseOutPackages::with('expense', 'expenseProof')->get();

        foreach($expenses as $expense)
        if($expense->expense_proof_id == null)
        {
            $expense->expense_proof_id = 0;
        }
        return $expenses;
    }
}
