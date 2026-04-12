<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterAttachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organisation_newsletter_id',
        'original_name',
        'stored_path',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public function newsletter()
    {
        return $this->belongsTo(OrganisationNewsletter::class, 'organisation_newsletter_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
