<?php
/**
 * Created by PhpStorm.
 * User: NB21334
 * Date: 15/04/2015
 * Time: 10:29
 */
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
use App\User;
use App\Group;
use App\Ticket;
use App\DB;

/**
 * Simple connectivity function to Postgres database. Returns connectivity variable.
 * connect function is hidden in connString php file
 * function connect(){
 * $dbconn = pg_connect("connection string") or die('Could not connect: ' . pg_last_error());
 * return $dbconn; }
 * @return resource
 */
function connect(){
    //$dbconn = pg_connect("host=10.200.10.54 port=5432 dbname=otrs user=otrsro password=otrs-ro123.")
    $dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=otrspg password=root")
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
 * Retrieves latest tickets from database and compiles them into a JSON string. Results should be decoded before consumption.
 * Read on for query related specific commentary.
 * @return string
 */
function getTicketsFromLastWeek(){

    /*
    |--------------------------------------------------------------------------
    | What does this query do?
    |--------------------------------------------------------------------------
    |
    | This query not only fetches all the tickets, it also matches all their information
    | across tables, so you don't have to lookup what state id of 4 means for example
    | the text is already added for priority SLA and whatnot
    | TODO: add the timestamp for time left till SLA expires to this query
    */

    $query = "select ti.id, ti.title, ti.user_id, us.first_name, us.last_name, sl.name AS sla_name, tp.name AS priority, tp.id AS priority_id, ts.name AS ticket_state, ti.timeout, ti.create_time AS cretime, ti.change_time AS chgtime
from ticket ti, users us, sla sl, ticket_priority tp, ticket_state ts
where ti.user_id=us.id AND ti.sla_id=sl.id AND ti.ticket_priority_id=tp.id AND ti.ticket_state_id=ts.id";
//AND ti.create_time >= ti.create_time - interval '7 days' limit 50

    /*
    |--------------------------------------------------------------------------
    | Not all tickets were moved from OTRS?
    |--------------------------------------------------------------------------
    |
    | Some tickets (about 2% of them), were not copied from OTRS to mysql DB.
    | The reason for this is simple, the tickets that weren't moved don't have an sla_id
    | Meaning they are not eligible for the game. We can query these tickets and figure out
    | they are either test tickets or internal functioning orders.
    |
    | the query that shows unmoved tickets:
    |
    |   SELECT ti.title ,ti.id, ti.create_time, ti.ticket_priority_id, ti.ticket_state_id
    |   FROM ticket ti
    |   WHERE ti.ticket_state_id != 2 AND ti.id NOT IN (
    |       select ti.id
    |       from ticket ti, users us, sla sl, ticket_priority tp, ticket_state ts
    |       where ti.user_id=us.id AND ti.sla_id=sl.id AND ti.ticket_priority_id=tp.id AND ti.ticket_state_id=ts.id
    |   )ORDER BY create_time
    |
    |
    | To have this query run simply change the variable in the string $query,
    | or create a new one and alter it's name in the function call pg_query()
    | The code following will look for a $query to parse the result into a json
    |
    */

    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    return json_encode(array_values(pg_fetch_all($result)));
}


/**
 * Same as getTicketsFromLastWeek() but only fetches tickets whose id is greater than the last one of localDB
 * In other words it syncs OTRS and localDB ensuring both have the same tickets.
 *
 * @param $last
 * @return string
 */
function getTicketsFromLastId($last){
    $query = "select ti.id, ti.title, ti.user_id, us.first_name, us.last_name, sl.name AS sla_name, tp.name AS priority, tp.id AS priority_id, ts.name AS ticket_state, ti.timeout, ti.create_time AS cretime, ti.change_time AS chgtime
from ticket ti, users us, sla sl, ticket_priority tp, ticket_state ts
where ti.user_id=us.id AND ti.sla_id=sl.id AND ti.ticket_priority_id=tp.id AND ti.ticket_state_id=ts.id AND ti.id>$last";

    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    return json_encode(array_values(pg_fetch_all($result)));
}

/**
 * Inserts data retrieved from OTRS into localDB
 * Lynked to insertChunkToDB
 * @param $lastId
 * @return string
 */
function syncDBs($lastId){
    $dal = connect();

    $query = "select ti.id, ti.title, ti.user_id, us.first_name, us.last_name, sl.name AS sla_name, tp.name AS priority,
    tp.id AS priority_id, ts.name AS ticket_state, ti.timeout, ti.create_time AS cretime, ti.change_time AS chgtime,
    q.group_id
    from ticket ti, users us, sla sl, ticket_priority tp, ticket_state ts, queue q, groups g
    where ti.user_id=us.id AND ti.sla_id=sl.id AND ti.ticket_priority_id=tp.id AND ti.ticket_state_id=ts.id
    AND q.group_id = g.id AND ti.queue_id = q.id AND ti.id>=$lastId order by ti.id;";

    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $data = (array_values(pg_fetch_all($result)));

    $chunkOfData = array_chunk($data, 1000);
    foreach ($chunkOfData as $chunk) {
        insertChunkToDB($chunk);
    }

    closeDB($dal);
}

/**
 * Inserts each chunk into Mysql
 * @param $chunk
 * @return Exception|ModelNotFoundException
 */
function insertChunkToDB($chunk){
    foreach ($chunk as $element) {
        try{
            $ticket = new App\Ticket();
            $ticket->id = $element['id'];
            $ticket->title = $element['title'];
            $ticket->user_id = $element['user_id'];
            $ticket->priority = $element['priority'];
            $ticket->state = $element['ticket_state'];
            $ticket->points = $element['priority_id'] * rand(5, 15);
            $ticket->sla = $element['sla_name'];
            $ticket->created_at = $element['cretime'];
            $ticket->updated_at = $element['chgtime'];
            $ticket->timeout = $element['timeout'];
            $ticket->assignedGroup_id = $element['group_id'];
            $ticket->save();
        } catch(ModelNotFoundException $e)  {
            return $e;
        }
    }
}

/***********************************************************************************************************************
 *
 *                              Separator - functions bellow are "virtually ran only one"
 *
 * The bellow functions migrate entire tables from OTRS to local DB
 * Because they are so heavy, you need to unprotect php and have no memory or execution time restrictions.
 **********************************************************************************************************************/

/*
|--------------------------------------------------------------------------
| The logic
|--------------------------------------------------------------------------
|
| The following functions are establishing a connection with postgres DB
| and fetching relevant rows, parsing them to json. These are called fill TableName Tables (in camelCase)
|
| the functions "add TableName Table" (again in camelCase) connect the DB, call the respective
| previously mentioned function and parse their result, iteration and creating objects using Eloquent
| Lastly they persist the result to the local mysql DB.
|
| The exception here is the addUserGroups - since there is no direct Eloquent model, it directly
| connects to the mysql DB and adds the table information.
*/

/**
 * Copy the tickets relation table from OTRS to localDB
 */
function addTicketsTable(){
    $dal = connect();
    $jsonData = json_decode(getTicketsFromLastWeek(),true);

    foreach ($jsonData as $ti) {
        $ticket = new App\Ticket();
        $ticket->id = $ti['id'];
        $ticket->title = $ti['title'];
        $ticket->user_id = $ti['user_id'];
        $ticket->priority = $ti['priority'];
        $ticket->state = $ti['ticket_state'];
        $ticket->points = $ti['priority_id'] * rand(5, 15);
        $ticket->sla = $ti['sla_name'];
        $ticket->created_at = $ti['cretime'];
        $ticket->updated_at = $ti['chgtime'];
        $ticket->timeout = $ti['timeout'];
        $ticket->save();
    }
    closeDB($dal);
}


/**
 * Retrieves all of the users from OTRS and compiles into a JSON string.
 * Based on getTicketsFromLastWeek()
 * @return array
 */
function fillUserTables(){
    $query = "SELECT u.id, u.login, u.first_name, u.last_name, u.title FROM users u";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $jsonData = array_values(pg_fetch_all($result));
    return $jsonData;
}


/**
 * Copy the user relation table from OTRS to localDB
 */
function addUsers(){
    $dal = connect();
    $jsonData = fillUserTables();

    foreach ($jsonData as $us) {
        $user = new App\User;
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
    closeDB($dal);
}


/**
 * Fetch group relation table from OTRS
 * @return array
 */
function fillGroupTables(){
    $query = "SELECT id ,name, comments  FROM groups";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $jsonData = array_values(pg_fetch_all($result));
    return $jsonData;
}


/**
 * Copy the Group relation table from OTRS to localDB
 */
function addGroups(){
    $dal = connect();
    $jsonData = fillGroupTables();

    foreach ($jsonData as $grp) {
        $group = new App\Group;
        $group->id = $grp['id'];
        $group->title = $grp['name'];
        $group->variant_name = $grp['comments'];
        $group->points = 0;
        $group->save();
    }
    closeDB($dal);
}


/**
 * Fetch user-group relation table from OTRS
 * @return array
 */
function fillGroupUserRelationTables(){
    $query = "SELECT user_id, group_id  FROM group_user";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $jsonData = array_values(pg_fetch_all($result));
    return $jsonData;
}


/**
 * Copy the User-Group relation table from OTRS to localDB
 */
function addUserGroups(){
    $dal = connect();
    $jsonData = fillGroupUserRelationTables();

    foreach ($jsonData as $rel) {
        DB::table('group_user')->insert(array(
            array('user_id' => $rel['user_id'], 'group_id' => $rel['group_id']),
        ));
    }
    closeDB($dal);
}


/*IDEA ON HOLD TO USE TICKET TIME SPENT
 * $ticket->timeout = $ti['changetime'] - $ti['createtime'];
$start = strtotime($ti['createtime']);
$end = strtotime($ti['changetime']);
$interval = $end - $start;
$intervalo = gmdate("Y-m-d\TH:i:s\Z", $interval);
var_dump(gmdate("Y-m-d\TH:i:s\Z", 0));*/
/*
 * FUNCTION PENDING DELETION: I'm putting on hold the idea to get the time spent on a ticket.
 * The logic that was under construction here would be to query the table 'ticket_history' which logs ticket activity
 * Given that I would do things like
 * Register time when a ticket was opened/closed and add points based on it's last given priority
 * If the ticket would enter the state of reopen (id of 14 maybe?) then decrease the points
 * This will return the value in points of the ticket.
 *
function analyseTicketHistory($ticketid){

    $query = "SELECT owner_id, priority_id, state_id, create_time, change_time FROM ticket_history WHERE id=$ticketid";
    echo $query . '<br/>';
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());

    $rows = array();
    while($r = pg_fetch_assoc($result)) {
        $rows[] = $r;

    }
    var_dump($rows);
    return 0;
}
*/