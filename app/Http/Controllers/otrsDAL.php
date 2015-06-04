<?php
/**
 * Created by PhpStorm.
 * User: NB21334
 * Date: 15/04/2015
 * Time: 10:29
 */
//ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
use App\User;
use App\Group;
use App\Ticket;
use App\DB;
use Illuminate\Support\Facades\App;

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
	ti.timeout, /*unix timestamp to when ticket was created*/
	ti.create_time AS cretime,
	ti.change_time AS chgtime
from ticket ti, users us, sla sl, ticket_priority tp, ticket_state ts, queue q, groups g
where ti.user_id=us.id AND ti.sla_id=sl.id AND ti.ticket_priority_id=tp.id AND ti.ticket_state_id=ts.id
	AND q.group_id = g.id AND ti.queue_id = q.id AND ti.id>$lastId order by ti.id;";

    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $data = (array_values(pg_fetch_all($result)));

    $chunkOfData = array_chunk($data, 1000);
    foreach ($chunkOfData as $chunk) {
        insertChunkToDB($chunk);
    }

    closeDB($dal);
}

/**
 * Insert each chunk
 * @param $chunk
 * @return Exception
 */
function insertChunkToDB($chunk){

    foreach ($chunk as $element) {
        try{
            $ticket = new Ticket();
            $ticket->id = $element['id'];
            $ticket->title = $element['title'];
            $ticket->user_id = $element['user_id'];
            User::firstOrCreate(array('id' => $element['user_id']));
            importUser($element['user_id']);
            $ticket->assignedGroup_id = $element['group_id'];
            Group::firstOrCreate(array('id' => $element['group_id']));
            importGroup($element['group_id']);
            $ticket->sla = $element['sla_name'];
            $ticket->sla_time = $element['solution_time'];
            $ticket->priority = $element['priority'];
            $ticket->state = $element['ticket_state'];
            $ticket->points = 0; //$element['priority_id'] * rand(5, 15);
            $ticket->created_at = $element['cretime'];
            $ticket->updated_at = $element['chgtime'];
            $ticket->timeout = $element['timeout'];

            $ticket->save();
        } catch(Exception $e)  {
            echo 'Exception, Dev stopped this because <br/>'.$e;
            exit(1);
        }
    }
}

function importUser($id){
    $query = "SELECT u.id, u.login, u.first_name, u.last_name, u.title FROM users u WHERE u.id=$id";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $resultData = array_values(pg_fetch_all($result));
    //var_dump($resultData[0]['login']); gives you the login of the first user as a string
    $user = User::find($id);
    //$user->id = $resultData[0]['id'];
    $user->name = $resultData[0]['login'];
    $user->email= $resultData[0]['login'] . "@novabase.com";
    $user->league_id = 1;
    $user->password = bcrypt('password');
    if($resultData[0]['title']){ $user->title = $resultData[0]['title']; }
    else { $user->title = "novice"; }
    $user->full_name = $resultData[0]['first_name'] . " " . $resultData[0]['last_name'];
    $user->points = 0;
    $user->health_points = 100;
    $user->experience = 0;
    $user->level = 1;
    $user->save();
}

function importGroup($id){
    $query = "SELECT id ,name, comments  FROM groups WHERE id=$id";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $resultData = array_values(pg_fetch_all($result));
    $group = Group::find($id);
    $group->title = $resultData[0]['name'];
    $group->variant_name = $resultData[0]['comments'];
    $group->points = 0;
    $group->save();
}