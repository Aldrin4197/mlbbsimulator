<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Match as MatchModel;
use App\Models\Player;

class Team extends Model
{
    protected $primaryKey = 'team_id'; // Assuming your PK is team_id

    protected $fillable = [
        'team_name',
        'short_name',
        'address',
        'team_logo',
    ];

public function players()
{
    return $this->hasMany(Player::class, 'team_id', 'team_id');
    //           ^ Specifies foreign key  ^ Specifies local key
}


}
