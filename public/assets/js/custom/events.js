function events(){


  /**
   * bellow here there's only event handling
   * -> Pagination functions
   * -> search bar for tickets
   * -> Date pickers for advanced search (time travel)
   * -> Button click triggers time travel (new ajax call)
   * */
  $(".next").click(function() {
    _globalpage++;
    updatePageNumber();
    leaderboardPaginator(_groupJson);
    ticketPaginator(_ticketsJson);
    drawMorrisBarGraph();
  });

  $(".previous").click(function() {
    _globalpage--;
    updatePageNumber();
    leaderboardPaginator(_groupJson);
    ticketPaginator(_ticketsJson);
    drawMorrisBarGraph();
  });


  //search event handling
  $("#ticketSearchField").change(function() {
    var searchResults = searchTickets($("#ticketSearchField").val());
    if (searchResults[0] == 'no results') {
      $('#ticketList').empty();
      $('#ticketList').append('<p class="well">Sorry, these aren\'t the tickets you are looking for</p>');
    } else {
      ticketPaginator(searchResults);
    }
  });


  //Date pickers for advanced search
  $(function() {
    $("#startDatepicker").datepicker();
    $("#endDatepicker").datepicker();
  });

  //simple validation (if dates are inserted)
  $("#startDatepicker, #endDatepicker").change(function(field) {
    if ($("#startDatepicker").val() && $('#endDatepicker').val()) {
      $('#timeTravelTrigger').removeAttr('disabled');
    }
  });

  //renewing all ajax calls
  $("#timeTravelTrigger").click(function() {
    var start = replaceAll('/', '-', $('#startDatepicker').val());
    var end = replaceAll('/', '-', $('#endDatepicker').val());
    var ticketCall = new ticketsAjaxCall(start, end);
    ticketCall.onready = function() {
      //ajax call is made here
      $('#timeTravelTrigger').prop('disabled', true);
      renderGroupLeaderboard();
      //renderPlayerLeaderboard();
    }
  });


  $("#setTimeWeek").click(function() {
    $("#startDatepicker").val(moment().weekday(-7).format('M[/]D[/]YYYY')); // last Monday
    $('#endDatepicker').val(moment().weekday(-2).format('M[/]D[/]YYYY')); //Last Friday
    $('#timeTravelTrigger').prop('disabled', false);
  });

  $("#setTimeMonth").click(function() {
    $("#startDatepicker").val(moment().subtract(1,'months').startOf('month').format('M[/]D[/]YYYY')); // last Monday
    $('#endDatepicker').val(moment().subtract(1,'months').endOf('month').format('M[/]D[/]YYYY')); //Last Friday
    $('#timeTravelTrigger').prop('disabled', false);
  });


}
