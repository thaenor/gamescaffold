<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    public function league() {  return $this->belongsTo('League');    }

    public function reward() {  return $this->hasMany('Reward');      }

    public function article(){  return $this->hasMany('Article');     }

    public function groups()  {  return $this->belongsToMany('App\Group'); }


    public function updateUser($userid, $pts){
        $user = User::find($userid);
        $user->points += $pts;
        $user->experience += ($pts)/2;
        $user->checkLvlUp($user);
        $user->checkDeath($user);
        $user->save();
    }

    public function checkLvlUp($user)
    {
        $articleUpdater = new Article();
        if($user->experience > 100){
            $user->level += $user->experience/100;
            $user->save();
            $articleUpdater->postAutomatedMessageLevelUp($user->id,$user->level);
        }
    }

    public function checkDeath($user)
    {
        $articleUpdater = new Article();
        if($user->health_points < 0){
            $user->level -= 1;
            $user->experience = 0;
            $user->points -= 50;
            $user->health_points = 100;
            $user->save();
            $articleUpdater->postAutomatedMessageDead($user->full_name);
        }
    }
}
