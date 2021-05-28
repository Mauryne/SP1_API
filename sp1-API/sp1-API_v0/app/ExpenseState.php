<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseState extends Model
{
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
