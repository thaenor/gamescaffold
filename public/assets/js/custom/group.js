// group class representation in Javascript
function Team(name, variant_name, points) {
    this.name = name;
    this.poinst = points;
    this.variant_name = variant_name;
    this.points = points;
    this.getName = function() {
      return this.name;
    };

}

function renderGroupLeaderboard() {
  var teamsArray = {}; //Dictionary like array, will contain [team name][team's points]... etc
  $.each(_ticketsJson, function(index, currentTicket) {
    if(teamsArray[currentTicket.assignedGroup_id] == null){
      teamsArray[currentTicket.assignedGroup_id] = 0;
    }

    teamsArray[currentTicket.assignedGroup_id] += currentTicket.points;
  });
  teamsArray ? redisplayGroupLeaderboard(teamsArray) : showGroupLeaderBoardError();
}


function renderPlayerLeaderboard() {
  var playerArray = {}; //Dictionary like array, will contain [team name][team's points]... etc
  $.each(_ticketsJson, function(index, currentTicket) {
    if(playerArray[currentTicket.user_id] == null){
      playerArray[currentTicket.user_id] = 0;
    }

    playerArray[currentTicket.user_id] += currentTicket.points;
  });
  playerArray ? DrawplayerLeaderboard(playerArray) : alert('no players exists');
}
function DrawplayerLeaderboard(array) {
  $('#playerLeaderboard').empty();
  $.each(array, function(index, el) {
    $('#playerLeaderboard').append(index + ' ' + el + '<hr/>');
    //fillBarGraphData(index, el);
  });
}



function redisplayGroupLeaderboard(array){
  $('#grouplist').empty();
  $('.hidden-sm').remove();
  $.each(array, function(index, el) {
    $('#grouplist').append('<tr> <td class="success">' + index + '</td>' + '<td class="info">' + el + '</td> </tr>');
    fillBarGraphData(index, el);
  });
}

function showGroupLeaderBoardError(){
  $("#table-resp").empty().append('No data was returned from the server. Our front-end dev deserves a woopin!');
}

function groupsAjaxCall() {
  this.onready = function() {}; // Our onready function
  this.response = {}; // The response Variable
  var self = this; // "References" this scope and all the "this" variables

  $.ajax({
    url: '/api/v1/groups',
    dataType: 'json',
    success: function(json) {
      self.response = json; // Sets the response
      self.onready.apply(self); // Calls the callback

      // get the `groups` array
      _groupJson = json;
      //sort groups by points. So highest scoring comes first
      _groupJson.sort(function(a, b) {
        return parseFloat(b.points) -
          parseFloat(a.points)
      });

      _globalpage = 1;
      leaderboardPaginator(_groupJson);
      drawMorrisBarGraph();
    },
    error: function() {
      console.error(
        'error while making ajax request to retrieve groups json file. If you are a dev I\'m from main.js'
      );
    }
  });
}


/**
 * Draws a page in the leaderboard. Currently this displays _recPerPage records per page
 */
function leaderboardPaginator(groups) {
  $('#grouplist').empty();

  var page = _pagination[_pageTab],
    startRec = Math.max(page - 1, 0) * _recPerPage,
    endRec = Math.min(startRec + _recPerPage, groups.length)
  recordsToShow = groups.slice(startRec, endRec);

  // loop through the array to populate your list
  $.each(recordsToShow, function(i, currentGroup) {
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
