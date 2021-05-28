<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpensePackage extends Model
{
    protected $table = 'expense_package_types';

    public function expensesIn()
    {
        return $this->hasMany(ExpenseInPackages::class, 'expense_package_type_id');
    }
}
