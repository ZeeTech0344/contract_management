<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAndHead extends Model
{
    use HasFactory;


    protected $fillable = [
        "employee_id",
        "basic_salary",
        "advance",
        "day_of_work_deduction",
        "amount",
        "salary_month",
        "status",
        "pendings",
        "addition",
        "day_of_work",
        "remarks",
        "account_id",
        "account_name"
    ];



    public function salary()
    {
        return $this->hasMany(salary::class, "employee_id", "id");
    }

    public function bank()
    {
        return $this->hasMany(Bank::class, "employee_id", "id");
    }


    public function pendings()
    {
        return $this->hasMany(Pending::class, "employee_id", "id");
    }

}
