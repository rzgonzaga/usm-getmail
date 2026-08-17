<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusTerm extends Model
{
    protected $fillable = ['campus_id', 'campus_name', 'org_unit', 'tenant_id', 'term_id'];
}
