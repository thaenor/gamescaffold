<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model {

    public static function getResolvedTicketsFromLastMonth (){
        $carbon = new Carbon('first day of February 2015', 'Europe/London');
        return Ticket::where('created_at', '>=', $carbon )->where('state','=','Resolved')->get();
    }

    public static function getCanceledTicketsFromLastMonth (){
        $carbon = new Carbon('first day of February 2015', 'Europe/London');
        //$tickets = Ticket::where('created_at', '>=', $carbon )->where('state','=','ReOpened')->get();
        return Ticket::where('created_at', '>=', $carbon )->where('state','=','Canceled')->get();
    }

    public function setTicketPoints(){
        $message = 'success';
        $player = new User();
        $tickets = Ticket::getResolvedTicketsFromLastMonth();
        if($tickets){
            foreach($tickets as $t){
                $player->updateUser($t->user_id, $t->points);

                //$teams = $user->groups;
                //foreach($teams as $team){
                //    $team->points += $t->points;
                //    $team->save();
                //}
            }
        } else {
            return $message = 'no tickets returned';
        }
        return $message;
    }

    public function setTicketPenalties(){
        $message = 'success';
        $player = new User();
        $tickets = Ticket::getCanceledTicketsFromLastMonth();
        if($tickets){
            foreach($tickets as $t){
                $player->updateUser($t->user_id, (-10));

                //get all the teams the user belongs to
                //TODO: review this bit of code. Please refer to the Todoist.com bug https://todoist.com/showTask?id=69626704
                //$teams = $user->groups;
                //foreach($teams as $team){
                //    $team->points -= $t->points;
                //    $team->save();
                //}
            }
        } else {
            return $message = 'no tickets returned';
        }
        return $message;
    }
}
