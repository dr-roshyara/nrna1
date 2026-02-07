<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'settings',
        'languages',
    ];

    protected $casts = [
        'settings' => 'array',
        'languages' => 'array',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organization_roles')
                    ->withPivot('role', 'permissions')
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
