<?php

namespace App\Http\Controllers;

use App\ExpenseSheetState;
use Illuminate\Http\Request;

class ExpenseSheetStateController extends Controller
{
    public function getOne(ExpenseSheetState $expenseSheetstate)
    {
        return $expenseSheetstate;
    }
}
