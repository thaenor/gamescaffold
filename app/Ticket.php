<?php namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model {

    public static function getResolvedTicketsBetween($start, $end){
        return Ticket::where('created_at', '>=', $start)->where('created_at', '<=', $end)->where('state','=','Resolved')->get();
    }

    public static function getReOpenedTicketsBetween ($start, $end){
        return Ticket::where('created_at', '>=', $start)->where('created_at', '<=', $end)->where('state','=','ReOpened')->get();
    }

    public static function getOpenTicketsBetween ($start, $end){
        return Ticket::where('created_at', '>=', $start)->where('created_at', '<=', $end)->where('state','=','open')->get();
    }

    /**
     * This is the start of the point calculation method.
     * The models will be reviewed as points are distributed
     * @return string
     */
    public function setTicketPoints(){
        $player = new User();
        $team = new Group();
        $carbon = new DateTime('first day of this month');
        $tickets = Ticket::getResolvedTicketsBetween($carbon, Carbon::now());
        if($tickets){
            foreach($tickets as $ticket){
                $ticket->updateTicketPoints();
                $player->updateUser($ticket->user_id, $ticket->points);
                $team->updateTeam($ticket->assignedGroup_id, $ticket->points);
            }
        } else {
            //TODO: Do some error handling
            exit(1);
        }
    }

    public function updateTicketPoints(){
        $priority = filter_var($this->priority, FILTER_SANITIZE_NUMBER_INT);
        $priorityInt = intval($priority);

        $created = strtotime($this->created_at); //This is a unix timestamp
        $updated = strtotime($this->updated_at);
        $slaSolutionTime = $this->sla_time;
        $timeSpentSolving = $created - $updated; // == <seconds between the two times>
        $minutesSpentSolving = ($timeSpentSolving/60)/60; // convert that into hours

        $formula = (($slaSolutionTime - $minutesSpentSolving) + rand(2,4) / $priorityInt);
        $this->points = $formula;
        $this->save();
    }
    
    public function setTicketPenalties(){
        $player = new User();
        $team = new Group();
        $carbon = new DateTime('first day of this month');
        $tickets = Ticket::getReOpenedTicketsBetween($carbon, Carbon::now());
        if($tickets){
            foreach($tickets as $t){
                $player->updateUser($t->user_id, (-10));
                $team->updateTeam($t->assignedGroup_id, $t->points);
            }
        } else {
            //TODO: Do some error handling
            exit(1);
        }
    }
}
