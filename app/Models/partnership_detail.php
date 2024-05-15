<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class partnership_detail extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'client_id',
        'contractor_id',
        'invoice_no',
        'percentage'
    ];

    function getClientData(){
        return $this->belongsTo(BuyerPurchaserDetail::class, "client_id");
    }

    function getOneRecordClient(){
        return $this->hasOne(BuyerPurchaserDetail::class, "id" ,"client_id");
    }

    function getContractor(){
        return $this->belongsTo(contractor_information::class, "contractor_id");
    }


}
