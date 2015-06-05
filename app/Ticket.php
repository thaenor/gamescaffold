<?php namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model {

    /**
     * Get the resolved tickets this month
     * @return mixed
     */
    public static function getResolvedTicketsForThisMonth (){
        $carbon = new DateTime('first day of this month');
        return Ticket::where('created_at', '>=', $carbon )->where('state','=','Resolved')->get();
    }

    /**
     * Get all the reopened tickets this month
     * @return mixed
     */
    public static function getReopenedTicketsForThisMonth (){
        $carbon = new DateTime('first day of this month');
        return $tickets = Ticket::where('created_at', '>=', $carbon )->where('state','=','ReOpened')->get();
    }

    /**
     * This is the start of the point calculation method.
     * The models will be reviewed as points are distributed
     * @return string
     */
    public function setTicketPoints(){
        $player = new User();
        $team = new Group();
        $tickets = Ticket::getResolvedTicketsForThisMonth();
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
        $tickets = Ticket::getReopenedTicketsForThisMonth();
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
