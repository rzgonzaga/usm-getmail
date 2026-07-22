<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusTerm extends Model
{
    protected $fillable = ['campus_id', 'campus_name', 'tenant_id', 'term_id'];
}
