<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveMeta extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_greater',
        'active_id',
        'zap_id'
    ];
}
