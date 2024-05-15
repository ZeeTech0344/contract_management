<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        "paid_date",
        "employee_id",
        "amount",
        "salary_month",
        "status",
        "account_id",
        "account_name"
    ];


    public function employee(){
        return $this->belongsTo(EmployeeAndHead::class, "employee_id");
    }
    
}
