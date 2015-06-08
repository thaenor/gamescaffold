/**
 * Created by NB21334 / Celfocus 2015.
 *
 * Player related functions
 */

function renderPlayerLeaderBoard() {
    var playerArray = {}; //Dictionary like array, will contain [team name][team's points]... etc
    var playerCounter = 0;

    $.each(_resolvedTicketsData, function(index, currentTicket) {
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
    //TODO sort player array and remove zeroes
    $.each(array, function(index, el) {
        //$('#playerLeaderboard').append(index + ' ' + el + '<hr/>');
        $('#playerLeaderboard').append('<tr> <td class="info"> <a class="playerModal" href="#"  data-toggle="modal" data-target="#playerInfo">'+index + '</a></td>' + '<td class="warning">' + el + '</td> </tr>' );
        //TODO: fill some graph here
        //fillBarGraphData(index, el);
    });
}
