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
 * Insert each chunk
 * @param $chunk
 * @return Exception
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
        } catch(Exception $e)  {
            App::error($e);
        }
    }
}