<?php namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use kintParser;

class Ticket extends Model {

    /**
     * Get all tickets with resolved status between a starting and an ending point
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function getClosedTicketsBetween($start, $end){
        return Ticket::where('created_at', '>=', $start)->where('created_at', '<=', $end)->where('state','=',"closed")->get();
    }

    /**
     * Get all tickets with reopened status between a starting and an ending point
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function getReOpenedTicketsBetween ($start, $end){
        return Ticket::where('created_at', '>=', $start)->where('created_at', '<=', $end)->where('state','=','ReOpened')->get();
    }

    /**
     * Get all tickets with open status between a starting and an ending point
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function getOpenTicketsBetween ($start, $end){
        return Ticket::where('created_at', '>=', $start)->where('created_at', '<=', $end)->where('state','=','open')->get();
    }

    public static function getAllOpenTickets(){
        return Ticket::where('state','=','open')->get();
    }

    /**
     * This is the start of the point calculation method.
     * The models will be reviewed as points are distributed
     * @return string
     */
    public function updateScorePoints($player_id, $team_id, $points){
        //player and team have already been created if they didn't exist so we know for sure they're there
        $player = User::find($player_id);
        $team = Group::find($team_id);
        $player->updateUser($points);
        $team->updateTeam($points);
    }

    public function updateTicketPoints($ticket){
        switch ($ticket->priority){
            case "1 Critical":
                $points = 10;
                break;
            case "2 High":
                $points = 8;
                break;
            case "3 Medium":
                $points = 3;
                break;
            case "4 Low":
                $points = 1;
                break;
            default:
                $points = 1;
        }

        switch($ticket->type){
            case "Incident":
                $points += 10;
                break;
            case "Service Request":
                $points += 5;
                break;
            case "Problem":
                $points += 7;
                break;
            default:
                $points += 1;
        }

        if( $ticket->percentage > 25){
            if($ticket->percentage < 100){
                $points = $points * ($ticket->percentage/100);
            }
            else if($ticket->percentage > 100){
                //set it to zero points but decrease HP
                $points = 0;
                $user = User::find($ticket->user_id);
                $user->takeDamage($ticket->percentage);
            }
        }
        $ticket->points = round($points);
        $ticket->save();
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
            exit(1);
        }
    }
}
