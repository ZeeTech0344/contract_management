<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplierData extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'head',
        'quantity',
        'amount',
        'total',
        'quantity',
        'amount_status'
      
    ];

    function getClientData(){
        return $this->belongsTo(BuyerPurchaserDetail::class, "supplier_id");
    }

    function getMultipleClient(){
        return $this->hasMany(BuyerPurchaserDetail::class, "id" ,"supplier_id");
    }

    function getOneRecordClient(){
        return $this->hasOne(BuyerPurchaserDetail::class, "id" ,"supplier_id");
    }

    function getOneRecordOfClient(){
        return $this->hasOne(BuyerPurchaserDetail::class, "id" ,"supplier_id");
    }
}
