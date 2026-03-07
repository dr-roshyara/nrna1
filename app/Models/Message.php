<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\BelongsToTenant;

class Message extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organisation_id',
        'from',
        'to',
        'message',
        'code',
        'message_receiver_id',
        'message_receiver_name',
        'message_sender_id',
        'message_sender_name',
    ];
}
