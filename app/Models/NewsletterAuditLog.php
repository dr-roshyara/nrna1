<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterAuditLog extends Model
{
    protected $fillable = [
        'organisation_newsletter_id',
        'organisation_id',
        'actor_user_id',
        'action',
        'metadata',
        'ip_address',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function newsletter()
    {
        return $this->belongsTo(OrganisationNewsletter::class, 'organisation_newsletter_id');
    }

    // Append-only — disallow updates
    public function save(array $options = []): bool
    {
        if ($this->exists) {
            return false;
        }
        return parent::save($options);
    }
}
