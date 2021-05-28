<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseOutPackages;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function updateOne(Request $request, $id)
    {
        $oneExpense = Expense::find($id);
        $oneExpense->quantity = $request->input('quantity');
        $oneExpense->amount = $request->input('amount');
        $oneExpense->expense_state_id = $request->input('expense_state_id');
        $oneExpense->save();
    }

    public function getOne(Expense $expense)
    {
        return $expense;
    }

    public function getAllByState(Request $request)
    {
        $expenses = Expense::all()->where('expense_state_id', '=', $request->expense_state_id);
        return $expenses;
    }

    public function getExpenseOutPackagesByExpenseSheet(Request $request)
    {
        $expenses = Expense::where('expense_sheet_id', $request->expenseSheet)
            ->with('expenseState')
            ->with('expensesOut')
            ->get();
        return $expenses;
    }

    public function getExpenseInPackagesByExpenseSheet(Request $request)
    {
        $expenses = Expense::where('expense_sheet_id', $request->expenseSheet)
            ->with('expenseState')
            ->with('expensesIn')
            ->get();
        return $expenses;
    }

    public function deleteOne($id)
    {
        Expense::find($id)->delete();
    }
}
