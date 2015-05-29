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
        $team = new Group();
        $tickets = Ticket::getResolvedTicketsFromLastMonth();
        if($tickets){
            foreach($tickets as $t){
                $player->updateUser($t->user_id, $t->points);
                $team->updateTeam($t->assignedGroup_id, $t->points);
            }
        } else {
            return $message = 'no tickets returned';
        }
        return $message;
    }

    public function setTicketPenalties(){
        $message = 'success';
        $player = new User();
        $team = new Group();
        $tickets = Ticket::getCanceledTicketsFromLastMonth();
        if($tickets){
            foreach($tickets as $t){
                $player->updateUser($t->user_id, (-10));
                $team->updateTeam($t->assignedGroup_id, $t->points);
            }
        } else {
            return $message = 'no tickets returned';
        }
        return $message;
    }
}
