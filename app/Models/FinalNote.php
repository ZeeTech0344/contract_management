<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalNote extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'client_id',
        'notes',
    ];

    function getClientData(){
        return $this->belongsTo(BuyerPurchaserDetail::class, "client_id");
    }

}
