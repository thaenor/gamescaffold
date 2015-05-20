<?php
/**
 * Created by PhpStorm.
 * User: NB21334
 * Date: 20/05/2015
 * Time: 12:08
 */

function connect(){
    //$dbconn = pg_connect("host=10.200.10.54 port=5432 dbname=otrs user=otrsro password=otrs-ro123.")
    //or die('Could not connect: ' . pg_last_error());
    $dbconn = pg_connect("host=localhost port=5432 dbname=postgres user=otrspg password=root")
    or die('Could not connect: ' . pg_last_error());

    return $dbconn;
}