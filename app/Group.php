<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Group extends Model {
    
    public function users()  {  return $this->belongsToMany('App\User'); }

    public function updateTeam($team_id, $pts){
        $team = Group::findOrFail($team_id);
        $team->points += $pts;
        $team->save();
    }
}
