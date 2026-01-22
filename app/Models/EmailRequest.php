<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailRequest extends Model
{
    // Explicit because "request" is a reserved word in Laravel helpers
    protected $table = 'email_requests';

    // Enable Laravel timestamps (created_at, updated_at)
    public $timestamps = true;

    protected $fillable = [
        'campus_id',
        'studentno',
        'firstname',
        'middlename',
        'lastname',
        'email',
        'status',
        'approve_by',
        'password',
    ];

    protected $casts = [
        'campus_id' => 'integer',
    ];
}
