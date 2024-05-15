<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'scope_id',
        'time_of_work',
        'team',
        'amount',
        'amount_type',
        'recieved_by',
        'remarks'
    ];

    function getClientData(){
        return $this->belongsTo(BuyerPurchaserDetail::class, "client_id");
    }

    function getScope(){
        return $this->belongsTo(supplierData::class, "scope_id");
    }
}
