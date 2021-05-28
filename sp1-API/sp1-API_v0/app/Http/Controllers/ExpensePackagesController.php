<?php

namespace App\Http\Controllers;

use App\ExpenseIn;
use App\ExpensePackage;
use Doctrine\Inflector\Rules\French\Rules;
use Illuminate\Http\Request;

class ExpensePackagesController extends Controller
{
    public function createElement(Request $request)
    {
        $ruleManagement = new ExpensePackage();
        $ruleManagement->name = $request->input("name");
        $ruleManagement->amount = $request->input("amount");
        $ruleManagement->save();
    }

    public function updateOne(Request $request, $id)
    {
        $ruleManagement = ExpensePackage::find($id);
        $ruleManagement->name = $request->input("name");
        $ruleManagement->amount = $request->input("amount");
        $ruleManagement->save();
    }

    public function deleteOne($id)
    {
//        $expensesIn = ExpenseIn::all()->where('expense_package_type_id', '=', $id);
        ExpensePackage::find($id)->delete();
//        foreach ($expensesIn as $oneExpenseIn)
//        {
//            $oneExpenseIn->delete();
//            $oneExpense->delete();
//        }
    }

    public function getOne(ExpensePackage $expensePackage)
    {
        return $expensePackage;
    }

    public function getAll()
    {
        return ExpensePackage::all();
    }

    public function deleteAll()
    {
        ExpensePackage::all()->delete();
    }
}
