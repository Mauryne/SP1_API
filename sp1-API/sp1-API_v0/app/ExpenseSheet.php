<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseSheet extends Model
{
    public function user()
    {
       return $this->belongsTo(User::class);

    }

    public function expenseSheetState()
    {
        return $this->belongsTo(ExpenseSheetState::class,'sheet_state_id');
    }
}
