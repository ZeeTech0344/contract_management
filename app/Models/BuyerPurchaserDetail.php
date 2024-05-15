<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerPurchaserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_no',
        'account_no',
        'cnic',
        'address',
        'status'
    ];

    function getInvoiceData(){
        return $this->hasMany(supplierData::class, "supplier_id", "id");
    }

    function getOneRecord(){
        return $this->hasOne(supplierData::class, "supplier_id", "id");
    }


    function getContractorInfo(){
        return $this->hasMany(partnership_detail::class, "client_id", "id");
    }

    function getExpense(){
        return $this->hasMany(ExpenseDetail::class, "client_id", "id");
    }
}
