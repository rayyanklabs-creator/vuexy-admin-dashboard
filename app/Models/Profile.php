<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'profile_image',
        'gender',
        'city',
        'zip',
        'street',
        'phone_number',
        'bio',
        'facebook_url',
        'linkedin_url',
        'instagram_url',
        'github_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
