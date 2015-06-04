function renderPlayerLeaderBoard() {
    var playerArray = {}; //Dictionary like array, will contain [team name][team's points]... etc
    $.each(_ticketsJson, function(index, currentTicket) {
        if(playerArray[currentTicket.user_id] == null){
            playerArray[currentTicket.user_id] = 0;
        }
        playerArray[currentTicket.user_id] += currentTicket.points;
    });
    playerArray ? showPlayerLeaderBoard(playerArray) : alert('no players exists');
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