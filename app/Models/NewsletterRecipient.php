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
        'user_id',
        'email',
        'name',
        'status',
        'idempotency_key',
        'error_message',
        'sent_at',
        'opened_at',
        'clicked_at',
        'consent_given_at',
        'consent_source',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'consent_given_at' => 'datetime',
    ];

    public function newsletter()
    {
        return $this->belongsTo(OrganisationNewsletter::class, 'organisation_newsletter_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
