// group class representation in Javascript
function group(id, title, variant_name, points) {
    this.id = id;
    this.title = title;
    this.variant_name = variant_name;
    this.points = points;
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

  /**
   * This should work.
   * The children() function returns a JQuery object that contains the children.
   * So you just need to check the size and see if it has at least one child.
   * #grouplist > * makes it slightly faster - I think...
   */
  if ($('#grouplist > *').length > 0) {
    $.each($('#grouplist').children(), function(i, current) {
      current.remove();
    });
  }
  /*Or you could just do $('#grouplist').empty()*/

  var page = _globalpage,
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
