<?php

namespace App\Http\Controllers;

use App\ExpenseSheet;
use App\ExpenseSheetState;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseSheetsController extends Controller
{
    public function updateOne(Request $request, $id)
    {
        $oneExpenseSheet = ExpenseSheet::find($id);
        $oneExpenseSheet->reference = $request->input('reference');
        $oneExpenseSheet->calculated_amount = $request->input('calculated_amount');
        $oneExpenseSheet->sheet_state_id = $request->input('sheet_state_id');
        $oneExpenseSheet->save();
    }

    public function getOne(ExpenseSheet $expenseSheet)
    {
        return $expenseSheet;
    }

    public function getAllByDate(Request $request)
    {
        return ExpenseSheet::all()->where('created_at', $request->date);
    }

    public function getAllInWaiting()
    {
        $expenses = ExpenseSheet::where('sheet_state_id', 6)
            ->with('user')
            ->with('expenseSheetState')
            ->get();
        return $expenses;
    }

    public function getAllUntreated()
    {
        $expenses = ExpenseSheet::where('sheet_state_id', 1)
            ->with('user')
            ->with('expenseSheetState')
            ->get();
        return $expenses;
    }

    public function getAllUntreatedByDate(Request $request)
    {
        return ExpenseSheet::all()->where('sheet_state_id', '=', 1)->where('creation_date', $request->date);
    }

    public function updateAllByDate(Request $request)
    {
        $expenseSheets = ExpenseSheet::all()->where('creation_date', $request->date);
        foreach ($expenseSheets as $oneExpenseSheet) {
            $oneExpenseSheet->calculated_amount = $request->input('calculated_amount');
            $oneExpenseSheet->save();
        }
    }

//    public function updateAllUntreated(Request $request)
//    {
//        $expenseSheets = ExpenseSheet::all()->where('sheet_state_id', '=', 1);
//        foreach ($expenseSheets as $oneExpenseSheet) {
//            $oneExpenseSheet->calculated_amount = $request->input('calculated_amount');
//            $oneExpenseSheet->save();
//        }
//    }

//    public function updateAllUntreatedByDate(Request $request)
//    {
//        $expenseSheets = ExpenseSheet::all()->where('sheet_state_id', '=', 1);
//        foreach ($expenseSheets as $oneExpenseSheet) {
//            $oneExpenseSheet->calculated_amount = $request->input('calculated_amount');
//            $oneExpenseSheet->save();
//        }
//    }

    public function getAllValidatedByDate(Request $request)
    {
        return ExpenseSheet::all()->where('sheet_state_id', '=', 3)->where('creation_date', $request->date);
    }


    public function getAllByMedicalVisitor($id)
    {
        return ExpenseSheet::all()->where('user_id', '=', $id);
    }

    public function getAllByMedicalVisitorByDate(Request $request, $id)
    {
        return ExpenseSheet::all()->where('user_id', '=', $id)->where('creation_date', $request->date);
    }
}
