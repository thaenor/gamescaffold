<?php namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use kintParser;
use Psy\Exception\Exception;

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

    public static function getAllTicketsBetween($start, $end){
        return Ticket::where('created_at', '>=', $start)->where('created_at', '<=', $end)->get();
    }

    /**
     * This is the start of the point calculation method.
     * The models will be reviewed as points are distributed
     * @return string
     */
    public function updateScorePoints($player_id, $team_id, $points){
        //player and team have already been created if they didn't exist so we know for sure they're there
        User::find($player_id)->updateUser($points);
        Group::find($team_id)->updateTeam($points);
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
                $points = 0;
        }

        switch($ticket->type){
            case "Incident":
                $points += 7;
                break;
            case "Service Request":
                $points += 5;
                break;
            case "Problem":
                $points += 10;
                break;
            default:
                $points += 0;
        }

        if( $ticket->percentage > 40){
            if($ticket->percentage < 100){
                $points = $points * ($ticket->percentage/100);
                $points = ceil($points);
            }
            else if($ticket->percentage > 100){
                $points = $points - ($points * ($ticket->percentage/100));
                $points = floor($points);
            }
        }
        $ticket->points = $points;
        if($ticket->state == "closed"){
            $this->updateScorePoints($ticket->user_id, $ticket->assignedGroup_id, $points);
        } else if($ticket->state == "ReOpen"){
            $this->setTicketPenalties();
        }
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

    public static function sync()
    {
        try{
            $lastTicketId = Ticket::take(1)->orderBy('id','desc')->first()->id;
            syncDBs($lastTicketId); //202325
        } catch(exception $e){
            Log::error('Overall sync error: '.$e);
            exit(1);
        }
        try{
            $lastId = Storage::disk('local')->get('lastid.txt');
            $newLastId = updateChangedTickets($lastId);
            Storage::disk('local')->put('lastid.txt', $newLastId);
        } catch(exception $e){
            Log::error('error updating ticket state: '.$e);
            exit(1);
        }
    }
}
