/**
 * Created by NB21334 / Celfocus 2015.
 *
 * Player related functions
 */

function renderPlayerLeaderBoard() {
    var playerArray = {}; //Dictionary like array, will contain [team name][team's points]... etc
    var playerCounter = 0;
    $.each(_ticketsJson, function(index, currentTicket) {
        if(playerArray[currentTicket.user_id] == null){
            playerArray[currentTicket.user_id] = 0;
            playerCounter++;
        }
        playerArray[currentTicket.user_id] += currentTicket.points;
    });
    playerArray ? (showPlayerLeaderBoard(playerArray), countPlayers(playerCounter)) : alert('no players exists');
}

function countPlayers(size){
    $('#playersInLeague').empty().append(size);
}

function showPlayerLeaderBoard(array) {
    $('#playerLeaderboard').empty();
    //TODO sort player array
    $.each(array, function(index, el) {
        $('#playerLeaderboard').append(index + ' ' + el + '<hr/>');
        //TODO: fill some graph here
        //fillBarGraphData(index, el);
    });
}