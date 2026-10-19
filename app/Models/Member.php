<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_code',
        'name',
        'email',
        'phone',
        'address',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
        ];
    }
}
