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
        'total_recipients',
        'sent_count',
        'failed_count',
        'idempotency_key',
        'queued_at',
        'completed_at',
    ];

    protected $casts = [
        'queued_at'    => 'datetime',
        'completed_at' => 'datetime',
        'deleted_at'   => 'datetime',
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
