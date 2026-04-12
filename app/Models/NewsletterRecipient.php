<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'organisation_newsletter_id',
        'member_id',
        'email',
        'name',
        'status',
        'idempotency_key',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function newsletter()
    {
        return $this->belongsTo(OrganisationNewsletter::class, 'organisation_newsletter_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
