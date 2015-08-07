<?php namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        return DB::select(DB::raw("
SELECT tickets.id,
tickets.title,
tickets.state,
tickets.type,
tickets.priority,
tickets.sla,
users.full_name AS user_id,
groups.title AS assignedGroup_id,
tickets.points,
tickets.percentage,
tickets.created_at,
tickets.updated_at,
tickets.external_id
FROM `tickets`
INNER JOIN users ON users.id = tickets.user_id
INNER JOIN groups ON groups.id = tickets.assignedGroup_id
WHERE(
    (tickets.created_at > '$start' AND tickets.created_at < '$end')
    OR (tickets.updated_at > '$start' AND tickets.updated_at < '$end')
    )
AND (state = 'closed' OR state = 'Resolved')
ORDER BY `id` DESC
        "));
    }

    /**
     * Get all tickets with reopened status between a starting and an ending point
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function getReOpenedTicketsBetween ($start, $end){
        return Ticket::whereBetween('created_at', [$start, $end])
            ->orWhereBetween('updated_at',[$start,$end])
            ->where('state','ReOpened')
            ->get();
    }

    /**
     * Get all tickets with open status between a starting and an ending point
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function getOpenTicketsBetween ($start, $end){
        return DB::select(DB::raw("
        SELECT tickets.id,
tickets.title,
tickets.state,
tickets.type,
tickets.priority,
tickets.sla,
users.full_name AS user_id,
groups.title AS assignedGroup_id,
tickets.points,
tickets.percentage,
tickets.created_at,
tickets.updated_at,
tickets.external_id
FROM `tickets`
INNER JOIN users ON users.id = tickets.user_id
INNER JOIN groups ON groups.id = tickets.assignedGroup_id
WHERE(
    (tickets.created_at > '$start' AND tickets.created_at < '$end')
    OR (tickets.updated_at > '$start' AND tickets.updated_at < '$end')
    )
AND (state != 'Resolved'
    OR state != 'closed'
    OR state != 'Cancelled'
    OR state != 'Solution Rejected')
ORDER BY `id` DESC
        "));
    }

    public static function getAllOpenTickets(){
        return Ticket::where('state','=','open')->get();
    }

    public static function getAllTicketsBetween($start, $end){
        return Ticket::whereBetween('created_at', [$start, $end])->get();
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
            $new_lastTicketId = Ticket::take(1)->orderBy('id','desc')->first()->id;
            echo ('a total of '.($new_lastTicketId-$lastTicketId).' new tickets were added /n');
        } catch(exception $e){
            Log::error('Overall sync error: '.$e);
            exit(1);
        }
        try{
            $lastId = Storage::disk('local')->get('lastid.txt');
            updateChangedTickets($lastId);
            $newLastId = updateLastTicketHistoryId();
            echo ('a total of '.($newLastId-$lastId).' tickets were updated /n');
            Storage::disk('local')->put('lastid.txt', $newLastId);
        } catch(exception $e){
            Log::error('error updating ticket state: '.$e);
            exit(1);
        }
    }

    public static function resetPoints()
    {
        $allGroups = Group::all()->get();
        foreach($allGroups as $group){
            $group->points = 0;
        }
        $allUsers = User::all()->get();
        foreach($allUsers as $user){
            $user->points = 0;
        }
    }
}
