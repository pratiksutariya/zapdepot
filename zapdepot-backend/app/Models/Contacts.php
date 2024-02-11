<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'phone',
        'email',
        'account_id',
        'resource',
        'user_id',
        'zap_id'
    ];
}
