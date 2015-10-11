/**
 * Created by NB21334 / Celfocus 2015.
 *
 * Player related functions
 */

function renderPlayerLeaderBoard() {
    var playerArray = {}; //Dictionary like array, will contain [player name][player's points]... etc
    var playerCounter = 0;
    var graphData = [];

    $.each(_resolvedTicketsData, function(index, currentTicket) {
        var obj={name:"", point:0};
        if(playerArray[currentTicket.user_id] === undefined){
            playerArray[currentTicket.user_id] = 0;
            obj.name=currentTicket.user_id; obj.point=0; graphData.push(obj);
            playerCounter++;
        }
        //if (currentTicket.points != fooCalculator(currentTicket)){
        //    console.log("ticket: "+currentTicket.points+" foo: "+fooCalculator(currentTicket)+" priority
        // "+currentTicket.priority+" sla "+ currentTicket.percentage+ " type "+currentTicket.type);
        //}
        playerArray[currentTicket.user_id] += fooCalculator(currentTicket);
        for(var i=0;i<graphData.length;i++){
            if(currentTicket.user_id === obj.name){ obj.point += fooCalculator(currentTicket); }
        }
        //console.log("player: "+currentTicket.user_id+" solved ticket with "+currentTicket.points+" priority"+currentTicket.priority+" type"+currentTicket.type+" sla "+currentTicket.sla_time+"%");
    });
    playerArray ? (showPlayerLeaderBoard(playerArray), countPlayers(playerCounter),renderMorrisBar_player(graphData)) : $.toaster({ priority : 'danger', title : 'Warning', message : 'no players exist'});
}

function renderMorrisBar_player(dataArray){
    $("#morris-bar-chart").empty();
    Morris.Bar({
        element: 'morris-bar-chart',
        data: dataArray,
        xkey: 'name',
        ykeys: ['point'],
        labels: ['points']
    });
}

function countPlayers(size){
    $('#playersInLeague').empty().append(size);
}

function showPlayerLeaderBoard(array) {
    $('#playerLeaderboard').empty();
    var orderedPlayers = sortByPoints(array);
    $.each(orderedPlayers, function(index, el) {
        //$('#playerLeaderboard').append(index + ' ' + el + '<hr/>');
        $('#playerLeaderboard').append('<tr> <td class="info"> <a href="#"  data-toggle="modal" data-target="#playerInfo">'+orderedPlayers[index][0] + '</a></td>' + '<td class="warning">' + orderedPlayers[index][1]  + '</td> </tr>' );
        //TODO: fill some graph here
        //fillBarGraphData(index, el);
    });
}

function sortByPoints(array) {
    var sortable = [];
    for(var player in array){
        sortable.push([player,array[player]])
        sortable.sort(function(a,b) {return b[1] - a[1]})
    }
    return sortable;
}

/**
 * Method that finds all tickets that belong to a specific player (playerToFind)
 * It looks into the attribute "user_id" of each ticket which actually contains
 * the name of the user that detains the ticket
 * This assumes no two players have the same name
 *
 * @param array
 * @param playerToFind
 * @returns {Array}
 */
function findPlayers(array, playerToFind){
    var foundMatches = [];
    for (var i=0; i < array.length; i++)
        if(array[i].user_id === playerToFind)
            foundMatches.push(array[i]);
    return foundMatches;
}
