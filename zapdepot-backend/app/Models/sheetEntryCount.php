<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sheetEntryCount extends Model
{
    use HasFactory;
    protected $fillable = [
        "zap_id",
        "count",
        "sender_tag_list_id",
        "receiver_tag_list_id",
    ];
}
