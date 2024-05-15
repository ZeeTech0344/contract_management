<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contractor_information extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'name',
        'phone_no',
        'account_no',
        'cnic',
        'address',
        'status'
    ];

    

}
