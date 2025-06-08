<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $primaryKey = 'player_id'; // 👈 required for route model binding
    protected $fillable = [
        'player_ign',
        'player_role',
        'player_photo',
        'team_id',
        'status',
    ];

    public function getRouteKeyName() // 👈 required for route model binding
    {
        return 'player_id';
    }

    // Relationship to Team
public function team()
{
    return $this->belongsTo(Team::class, 'team_id', 'team_id');
    //           ^ Specifies foreign key  ^ Specifies owner key
}
}
