<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'slug',
        'type',
        'address',
        'representative',
        'settings',
        'languages',
        'created_by',
    ];

    protected $casts = [
        'address' => 'array',
        'representative' => 'array',
        'settings' => 'array',
        'languages' => 'array',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organisation_roles')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function elections()
    {
        return $this->hasMany(Election::class);
    }

    public function admins()
    {
        return $this->users()->wherePivot('role', 'admin');
    }

    public function commissionMembers()
    {
        return $this->users()->wherePivot('role', 'commission');
    }

    public function voters()
    {
        return $this->users()->wherePivot('role', 'voter');
    }
}
