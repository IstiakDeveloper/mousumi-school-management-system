<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBalance extends Model
{
    use HasFactory;
    protected $fillable = ['balance'];

    public function addIncome($amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    public function deductExpense($amount)
    {
        $this->balance -= $amount;
        $this->save();
    }
}
