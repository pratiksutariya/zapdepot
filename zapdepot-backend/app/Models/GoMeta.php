<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoMeta extends Model
{
    use HasFactory;
    protected $fillable = [
        'go_account_id',
        'startAfter',
        'startAfterId',
        'zap_id'
    ];
}
