<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public function expenseState()
    {
        return $this->belongsTo(ExpenseState::class);
    }

    public function expenseActivityPackages()
    {
        return $this->hasMany(ExpenseActivityPackages::class);
    }

    public function expensesIn()
    {
        return $this->hasMany(ExpenseInPackages::class);
    }

    public function expensesOut()
    {
        return $this->hasMany(ExpenseOutPackages::class);
    }
}
