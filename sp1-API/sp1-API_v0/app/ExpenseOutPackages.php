<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseOutPackages extends Model
{
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function expenseProof()
    {
        return $this->belongsTo(ExpenseProof::class);
    }
}
