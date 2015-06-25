<?php
/**
 * Created by PhpStorm.
 * User: NB21334
 * Date: 15/04/2015
 * Time: 10:29
 */
//ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

use App\Group;
use App\User;
use App\Ticket;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Simple connectivity function to Postgres database. Returns connectivity variable.
 * connect function is hidden in connString php file
 * function connect(){
 * $dbconn = pg_connect("connection string") or die('Could not connect: ' . pg_last_error());
 * return $dbconn; }
 * @return resource
 */
function connect(){
    $dbconn = pg_connect("host=10.200.10.54 port=5432 dbname=otrs user=otrsro password=otrs-ro123.")
    //$dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=otrspg password=root")
    or die('Could not connect: ' . pg_last_error());

    return $dbconn;
}


/**
 * Function to close the connection. #NotRocketScience
 * @param $dbconn
 */
function closeDB($dbconn){
    pg_close($dbconn);
}


/**
 * Retrieves latest ticket ID from OTRS
 * @return int
 */
function getLastIDFromTickets(){
    try{
        $query = "select id from ticket order by id desc limit 1";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $data = (array_values(pg_fetch_all($result)));
        return intval($data[0]['id']);
    } catch(exception $e){
        Log::error('Error getting time from OTRS, more details: '.$e);
        echo $e;
        exit(1);
    }
}


/**
 * See TicketController->sync function
 * Inserts data retrieved from OTRS into localDB
 * Lynked to insertChunkToDB
 *
 * helpfull link: http://codepoets.co.uk/2014/postgresql-unbuffered-queries/
 *
 * @param $lastId
 * @return string
 */
function syncDBs($lastId){

    $query = "select ti.id,
	ti.title,
	ti.user_id,
	/*us.first_name,
	us.last_name,*/
	q.group_id AS group_id,
	/*q.name AS group_name,*/
	sl.name AS sla_name,
	sl.solution_time AS solution_time, /*tells me how long till an sla runs out in minutes*/
	tp.name AS priority,
	/*tp.id AS priority_id,*/
	ts.name AS ticket_state,
	/*ti.timeout, unix timestamp to when ticket was created*/
	ti.percentage,
	/*ti.type_id,*/
	type.name AS type_of_ticket,
	ti.create_time AS cretime,
	ti.change_time AS chgtime
from ticket ti, users us, sla sl, ticket_priority tp, ticket_state ts, queue q, groups g, ticket_type type
where ti.user_id=us.id AND ti.sla_id=sl.id AND ti.ticket_priority_id=tp.id AND ti.ticket_state_id=ts.id
	AND q.group_id = g.id AND ti.queue_id = q.id AND ti.type_id = type.id AND ti.id >= '$lastId' order by ti.id";

    try {
        $dal = connect();
        $otrsTicketLastId = getLastIDFromTickets();
        if($lastId === $otrsTicketLastId){
            Log::warning('Nothing to sync.');
            return;
        }
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $data = (array_values(pg_fetch_all($result)));
        $chunkOfData = array_chunk($data, 1000);
        foreach ($chunkOfData as $chunk) {
            insertChunkToDB($chunk);
        }
        closeDB($dal);
    } catch(exception $e){
        Log::error('Error syncing both databases, more details: '.$e);
        echo $e;
        exit(1);
    }
}

function updateChangedTickets($lastUpdateId){
    $query = "select th.id, th.ticket_id, tp.name AS priority, ts.name AS state, owner_id AS player_id, g.id AS team_id, tt.name AS ticket_type
 from ticket_history th, ticket_priority tp, ticket_state ts, users, queue, ticket_type tt, groups g
 where th.priority_id = tp.id
 AND queue.id = g.id
 AND th.state_id = ts.id
 AND owner_id = users.id
 AND th.queue_id = queue.id
 AND th.type_id = tt.id
 AND th.id > $lastUpdateId
 ORDER BY th.id desc";

    try {
        $dal = connect();
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        if(pg_fetch_result($result,0) === false){
            Log::warning('Nothing to sync.');
            return;
        }
        $data = (array_values(pg_fetch_all($result)));
        $chunkOfData = array_chunk($data, 100);
        foreach ($chunkOfData as $chunk) {
            updateChunkToDB($chunk);
        }
        closeDB($dal);
        return updateLastTicketHistoryId();
    } catch(exception $e){
        Log::error('Error syncing ticket updates, more details: '.$e);
        echo $e;
        exit(1);
    }
}


function updateLastTicketHistoryId(){
    $query = "select id from ticket_history order by id desc limit 1";
    $dal = connect();
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $data = pg_fetch_result($result, 0, 0);
    closeDB($dal);
    return $data;
}


/**
 * Insert each chunk
 * This function calls related functions to calculate points for each ticket.
 * It also defines the points for each player and team in DB.
 * New players and teams are created if they do not exist.
 * @param $chunk
 * @return Exception
 */
function insertChunkToDB($chunk){
    foreach ($chunk as $element) {
        $object = json_decode(json_encode($element), FALSE);
        insertTicketToDB($object);
    }
}

function updateChunkToDB($chunk){
    foreach($chunk as $element){
        $object = json_decode(json_encode($element), FALSE);
        updateTicketToDB($object);
    }
}

function insertTicketToDB($element){
    try{
        $ticket = Ticket::find($element->id);
        if(!$ticket){
            $ticket = new Ticket();
        }
        $ticket->id = $element->id;
        $ticket->title = $element->title;
        $ticket->type = $element->type_of_ticket;
        $ticket->priority = $element->priority;
        $ticket->state = $element->ticket_state;
        $ticket->sla = $element->sla_name;
        $ticket->sla_time = $element->solution_time;
        $ticket->percentage = $element->percentage;
        $ticket->created_at = $element->cretime;
        $ticket->updated_at = $element->chgtime;
        $ticket->user_id = $element->user_id;
        //tries to locate the user. If non existen, the data is imported
        $user = User::find($element->user_id);
        if(!$user){
            importUser($element->user_id);
        }
        //tries to locate the group. If non existen, the data is imported
        $group = Group::find($element->group_id);
        if(!$group){
            importGroup($element->group_id);
        }
        $ticket->assignedGroup_id = $element->group_id;
        //optional: importRelationUserGroup($element->user_id, $element->group_id);
        //point calculation
        $ticket->points = 0; //updateTicketPoints($element->type_of_ticket, $element->priority,$element->percentage);
        $ticket->save();
        //$ticket->updateScorePoints($element->user_id, $element->group_id, $ticket->points);
    } catch(Exception $e)  {
        Log::error('Error inserting chunk of tickets, execution stopped. more details on why:  '.$e);
        echo $e;
        exit(1);
    }
}


function updateTicketToDB($object){
    $ticket = Ticket::find($object->ticket_id);
    $ticket->type = $object->ticket_type;
    $ticket->assignedGroup_id = $object->team_id;
    $group = Group::find($object->team_id);
    if(!$group){
        importGroup($object->team_id);
    }
    $ticket->user_id = $object->player_id;
    $user = User::find($object->player_id);
    if(!$user){
        importUser($object->player_id);
    }
    $ticket->priority = $object->priority;
    $ticket->state = $object->state;
    $ticket->save();
}


/**
 * Function that calculates points of a ticket based on it's priority
 * @param $priority
 * @return int
 */
function updateTicketPoints($type, $priority, $percentage){
    switch ($priority){
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

    switch ($type){
        case "Incident":
            $points += 10;
            break;
        case "Service Request":
            $points += 3;
            break;
        case "Problem":
            $points += 5;
            break;
    }

    return $points;
}

/**
 * Function triggered when a user being imported does not exist in local DB
 * This fetches the related information and records it to local DB
 * @param $id
 */
function importUser($id){
    try{
        $query = "SELECT u.id, u.login, u.first_name, u.last_name, u.title FROM users u WHERE u.id=$id";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $resultData = pg_fetch_object($result);
		if($resultData == false){
            return;
        }
        $resultData = json_decode(json_encode($resultData), FALSE);
        $user = new User();
        $user->id = $resultData->id;
        $user->name = $resultData->login;
        $user->email= $resultData->login . "@novabase.com";
        $user->league_id = 1;
        $user->password = bcrypt('password');
        if($resultData->title){ $user->title = $resultData->title; }
        else { $user->title = "novice"; }
        $user->full_name = $resultData->first_name . " " . $resultData->last_name;
        $user->points = 0;
        $user->health_points = 100;
        $user->experience = 0;
        $user->level = 1;
        $user->save();
    } catch (exception $e){
        Log::error('Error updating user table. more details on why:  '.$e);
        echo $e;
    }

}

/**
 * Function triggered when a group being imported does not exist in local DB
 * This fetches the related information and records it to local DB
 * @param $id
 */
function importGroup($id){
    try{
        $query = "SELECT id ,name, comments  FROM groups WHERE id=$id";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $resultData = pg_fetch_object($result);
		if($resultData == false){
            return;
        }
        $resultData = json_decode(json_encode($resultData), FALSE);
        $group = new Group();
        $group->id = $resultData->id;
        $group->title = $resultData->name;
        $group->variant_name = $resultData->comments;
        $group->points = 0;
        $group->save();
    } catch (exception $e){
        Log::error('Error updating user table. more details on why:  '.$e);
        echo $e;
    }
}

function importRelationUserGroup($user_id, $group_id){
    try {
        $query = "select user_id, group_id from group_user where user_id=$user_id AND group_id=$group_id";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $resultData = array_values(pg_fetch_all($result));
        $chunkOfData = array_chunk($resultData, 1000);
        foreach ($chunkOfData as $chunk) {
            insertChunkUserGroupRelation($chunk);
        }
    }catch (exception $e){
        Log::error('Error updating user-group relation table. '.$e);
        echo $e;
    }
}

/***********************************************************************************************************************/
/***********************************************************************************************************************/
/***********************************************************************************************************************/
/***********************************************************************************************************************/
/***********************************************************************************************************************/
/***********************************************************************************************************************/
/**
 * Manual migration and bellow functions are only used
 * in case the local DB has been truncated or emptied.
 * They import all data from OTRS
 */
function manualMigration(){
    try{
        $dal = connect();
        fillUserTables();
        fillGroupTables();
        fillGroupUserRelationTables();
        closeDB($dal);
    } catch(exception $e){
        Log::error('Error on manual migration. More details: '.$e);
        echo $e;
        App::abort(403, 'Manual Migration fucked up '.$e);
    }
}

function fillUserTables(){
    $query = "SELECT u.id, u.login, u.first_name, u.last_name, u.title FROM users u";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $data = array_values(pg_fetch_all($result));
    $chunkOfData = array_chunk($data, 1000);
    foreach ($chunkOfData as $chunk) {
        insertUserChunkToDB($chunk);
    }
}
function insertUserChunkToDB($chunk){
    foreach ($chunk as $us) {
        $user = new User;
        $user->id = $us['id'];
        $user->name = $us['login'];
        $user->email= $us['login'] . "@novabase.com";
        $user->league_id = 1;
        $user->password = bcrypt('password');
        if($us['title']){ $user->title = $us['title']; }
        else { $user->title = "novice"; }
        $user->full_name = $us['first_name'] . " " . $us['last_name'];
        $user->points = 0;
        $user->health_points = 100;
        $user->experience = 0;
        $user->level = 1;
        $user->save();
    }
}


function fillGroupTables(){
    $query = "SELECT id ,name, comments  FROM groups";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $data = array_values(pg_fetch_all($result));
    $chunkOfData = array_chunk($data, 1000);
    foreach ($chunkOfData as $chunk) {
        insertGroupChunkToDB($chunk);
    }
}
function insertGroupChunkToDB($chunk){
    foreach ($chunk as $grp) {
        $group = new Group;
        $group->id = $grp['id'];
        $group->title = $grp['name'];
        $group->variant_name = $grp['comments'];
        $group->points = 0;
        $group->save();
    }
}

function fillGroupUserRelationTables(){
    $query = "SELECT user_id, group_id  FROM group_user";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $data = array_values(pg_fetch_all($result));
    $chunkOfData = array_chunk($data, 1000);
    foreach ($chunkOfData as $chunk) {
        insertChunkUserGroupRelation($chunk);
    }
}
function insertChunkUserGroupRelation($chunk){
    foreach ($chunk as $rel) {
        try {
           DB::table('group_user')->insert(array(
            array('user_id' => $rel['user_id'], 'group_id' => $rel['group_id']),
        )); 
       } catch (exception $e){
            Log::warning("the relation you are trying to create already exists, more details: ".$e);
            echo $e;
       }
        
    }
}
