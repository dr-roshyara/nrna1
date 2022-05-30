<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synchronization extends Model
{
    use HasFactory;
      public $incrementing = false;

    protected $fillable = [
        'token', 'last_synchronized_at'
    ];

    protected $casts = [
        'last_synchronized_at' => 'datetime',
    ];

    public function ping()
    {
        return $this->synchronizable->synchronize();
    }

    public function synchronizable()
    {
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($synchronization) {
            $synchronization->id = Uuid::uuid4();
            $synchronization->last_synchronized_at = now();
        });

        static::created(function ($synchronization) {
            $synchronization->ping();
        });
    }
}
