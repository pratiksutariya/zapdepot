<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GohighlevelAccounts extends Model
{
    use HasFactory;
    protected $fillable = [
        'api_key',
        'location_id',
        'type',
        'access_token',
        'refresh_token',
        'label',
        'user_id'
    ];
}
