<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'head',
        'amount'
    ];

    function getClientData(){
        return $this->hasOne(BuyerPurchaserDetail::class, "id" , "client_id");
    }

}
