<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganisationNewsletter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organisation_id',
        'created_by',
        'subject',
        'html_content',
        'plain_text',
        'status',
        'audience_type',
        'audience_meta',
        'total_recipients',
        'sent_count',
        'failed_count',
        'idempotency_key',
        'queued_at',
        'scheduled_for',
        'completed_at',
    ];

    protected $casts = [
        'audience_meta' => 'array',
        'queued_at'     => 'datetime',
        'scheduled_for' => 'datetime',
        'completed_at'  => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function recipients()
    {
        return $this->hasMany(NewsletterRecipient::class, 'organisation_newsletter_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(NewsletterAuditLog::class, 'organisation_newsletter_id');
    }

    public function attachments()
    {
        return $this->hasMany(NewsletterAttachment::class, 'organisation_newsletter_id');
    }

    public function getAudienceLabelAttribute(): string
    {
        return match($this->audience_type) {
            'all_members' => 'All Members',
            'members_full' => 'Full Members',
            'members_associate' => 'Associate Members',
            'members_overdue' => 'Members with Overdue Fees',
            'election_voters' => 'Election Voters',
            'election_not_voted' => 'Voters Who Haven\'t Voted',
            'election_voted' => 'Voters Who Already Voted',
            'election_candidates' => 'Candidates',
            'election_observers' => 'Observers',
            'election_committee' => 'Election Committee',
            'election_all' => 'All Election Participants',
            'org_participants_staff' => 'Staff',
            'org_participants_guests' => 'Guests',
            'org_admins' => 'Organisation Admins',
            default => $this->audience_type,
        };
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function failureRate(): float
    {
        $total = $this->sent_count + $this->failed_count;
        return $this->failed_count / max(1, $total);
    }

    public function isKillSwitchTriggered(): bool
    {
        $total = $this->sent_count + $this->failed_count;
        return $total >= 50 && $this->failureRate() > 0.20;
    }
}
