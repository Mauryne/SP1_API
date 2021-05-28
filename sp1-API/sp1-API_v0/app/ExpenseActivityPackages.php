<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseActivityPackages extends Model
{
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
