<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseInPackages extends Model
{
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function expensePackage()
    {
        return $this->belongsTo(ExpensePackage::class, 'expense_package_type_id');
    }
}
