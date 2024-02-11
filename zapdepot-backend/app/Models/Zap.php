<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zap extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "sender_name",
        "receiver_name",
        "receiver_id",
        "sender_id",
        "status",
        "receiver_tag_list_id", 
        "user_id", 
        "get_val", 
        "sender_tag_list_id", 
        "data_transfer_status"
    ];
}
