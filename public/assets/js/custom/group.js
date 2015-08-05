/**
 * Created by NB21334 / Celfocus 2015.
 *
 * Group related functions.
 */

function getGroupData(start,end){
    var link = generateLink('groups', start, end);
    getAjaxData(link).done(function(data){
        _groupJson = data;
        //sort groups by points. So highest scoring comes first
        _groupJson.sort(function (a, b) {
            return parseFloat(b.points) -
                parseFloat(a.points)
        });
        //TODO: remove elements with zero
        leaderBoardPagination(_groupJson);
        drawMorrisBarGraph();
        $('#notificationBox').empty().append('<p></p>');
    }).fail(function (){
        $.toaster({ priority : 'danger', title : 'Internal Error', message : 'Getting team score blew up the server!'});
    });
}

function renderGroupLeaderBoard(data) {
    var teamsArray = {}; //Dictionary like array, will contain [team name][team's points]... etc
    $.each(data, function (index, currentTicket) {
        if (teamsArray[currentTicket.assignedGroup_id] == null) {
            teamsArray[currentTicket.assignedGroup_id] = 0;
        }

        teamsArray[currentTicket.assignedGroup_id] += currentTicket.points;
    });
    teamsArray ? reDisplayGroupLeaderBoard(teamsArray) : showGroupLeaderBoardError();
}


function reDisplayGroupLeaderBoard(array) {
    $('#grouplist').empty();
    $('.hidden-sm').remove();
    var orderedTeams = sortByPoints(array);
    $.each(orderedTeams, function (index, el) {
        $('#grouplist').append('<tr> <td class="success">' + el[0] + '</td>' + '<td class="info">' + el[1] + '</td> </tr>');
        fillBarGraphData(el[0], el[1]);
    });
    $('#groupLeaderBoardNav').hide();
}

function showGroupLeaderBoardError() {
    $("#table-resp").empty().append('No data was returned from the server. The cleaning lady did it again!');
}


/**
 * Draws a page in the leaderBoard. Currently this displays _recPerPage records per page
 */
function leaderBoardPagination(groups) {
    $('#grouplist').empty();

    var page = _pagination[_pageTab],
        startRec = Math.max(page - 1, 0) * _recPerPage,
        endRec = Math.min(startRec + _recPerPage, groups.length);
    var recordsToShow = groups.slice(startRec, endRec);

    // loop through the array to populate your list
    $.each(recordsToShow, function (i, currentGroup) {
        // alternative - output data has a list. adds an option tag to your existing list
        //$('#yourlist').append(new Option( currentAirport.airport_name )); adds option tags with item
        //$('#grouplist').append('<li>'+ '<a href="#profile"" data-toggle="tab">'+ currentGroup.title + '</a>' +'</li>'); print names in list
        //draws has table. Column "variant name" is hidden on smaller screens
        $('#grouplist').append('<tr> <td class="success">' + currentGroup.title + '</td>' + '<td class="info hidden-xs hidden-sm">' + currentGroup.variant_name + '</td>' + '<td class="warning">' + currentGroup.points + '</td> </tr>');
        fillBarGraphData(currentGroup.title, currentGroup.points);
    });
}


/**
 * (Re)draws Morris bar graph displaying all teams.
 * Each time a new page is opened the old graphs aren't removed, to do that you'd have to either remove them
 * Or supply a copy of _barGraphDesignJson with only the desired data
 * */
function drawMorrisBarGraph() {
    $('#morris-bar-chart').empty();
    Morris.Bar({
        element: 'morris-bar-chart',
        data: _barGraphDesignJson,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Group name']
    });
}


/**
 * Fills global array @_barGraphDesignJson with name and points of each group.
 * This array will later be used by Morris lib to draw the graph
 */
function fillBarGraphData(title, points) {
    var tmp = {};
    tmp.y = title;
    tmp.a = points;
    _barGraphDesignJson.push(tmp);
}
