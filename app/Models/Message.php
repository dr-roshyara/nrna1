<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable =['from', 'to', 
    'message', 'code', 
    'message_receiver_id',
    'message_receiver_name',
    'message_sender_id',
    'message_sender_name',
    // 'messager_sender_id',
    // 'messager_sender_name'
];  

}
