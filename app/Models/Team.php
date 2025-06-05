<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $primaryKey = 'team_id';  // If your PK is team_id

    protected $fillable = [
        'team_name',
        'team_logo',
    ];
}