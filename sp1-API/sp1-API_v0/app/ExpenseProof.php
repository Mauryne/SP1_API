<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseProof extends Model
{
    public function expensesOut()
    {
        return $this->hasMany(ExpenseOutPackages::class);
    }
}
