// ticket class representation in Javascript
function ticket(id, title, user_id, points, priority, state, slaDesc, timeout, created_at) {
    this.id = id;
    this.title = title;
    this.user_id = user_id;
    this.points = points;
    this.priority = priority;
    this.state = state;
    this.slaDesc = slaDesc;
    this.timeout = timeout;
    this.created_at = created_at;
}

function replaceAll(find, replace, str) {
  return str.replace(new RegExp(find, 'g'), replace);
}


function ticketsAjaxCall(start, end) {
  this.onready = function() {}; // Our onready function
  this.response = {}; // The response Variable
  var self = this; // "References" this scope and all the "this" variables

  var link;
  if (start && end) {
    link = '/api/v1/tickets/' + start + '&' + end;
  } else {
    link = '/api/v1/tickets/';
  }

  $.ajax({
    url: link,
    dataType: 'json',
    success: function(json) {
      self.response = json; // Sets the response
      self.onready.apply(self); // Calls the callback
      if (json.length != 0) {
        _ticketsJson = json;
        ticketPaginator(_ticketsJson);
        calculateGroupPoints();
      } else {
        showTicketErrorMessage();
      }

    },
    error: function(xhr, ajaxOptions, thrownError) {
      if (xhr.status == 404) {
        console.error('404 Error - the date referenced a future time or was incorrect ' + thrownError);
      } else if (xhr.status == 400) {
        console.error('400 Error - the dates supplied were poorly formated. Input valid dates (let jQueryUI do it\'s work) ' + thrownError);
      } else {
        console.error('unknown error: ' + thrownError);
      }
      showTicketErrorMessage();
    }
  });
}


function showTicketErrorMessage() {
  $('#ticketList').empty().append('<div class="alert alert-danger" role="alert">Sorry, these aren\'t the tickets you are looking for...</div>');
}


/**
 * Draws a page in the tickets tab. Currently this displays _recPerPage records per page.
 * Both this and leaderboard page are synced. This means that clicking next will change to page 2 globally.
 * Not sure to view that as a feature or a bug.
 */
function ticketPaginator(tickets) {
  $('#ticketList').empty();
  var page = _globalpage,
    startRec = Math.max(page - 1, 0) * _recPerPage,
    endRec = Math.min(startRec + _recPerPage, tickets.length)
  recordsToShow = tickets.slice(startRec, endRec);
  $.each(recordsToShow, function(i, currentTicket) {
    if (currentTicket.priority == "2 High") {
      $('#ticketList').append('<li class="list-group-item list-group-item-danger"> <a href="#">' + currentTicket.title  + '<span class="pull-right">'+currentTicket.created_at+'</span> </a></li>');
    } else if (currentTicket.priority == "3 Medium") {
      $('#ticketList').append('<li class="list-group-item list-group-item-warning"> <a href="#">' + currentTicket.title + '<span class="pull-right">'+currentTicket.created_at+'</span> </a></li>');
    } else {
      $('#ticketList').append('<li class="list-group-item list-group-item-info"> <a href="#">' + currentTicket.title + '<span class="pull-right">'+currentTicket.created_at+'</span> </a></li>');
    }
  });
}


/**
 * Searches the ticket array for the contents of
 * the string received as parameter.
 * if no results are found the single element
 * "no results" is returned
 */
function searchTickets(searchString) {
  var searchResults = [];
  $.each(_ticketsJson, function(index, currentTicket) {
    if (currentTicket.title.includes(searchString)) {
      searchResults.push(currentTicket);
    }
  });

  if (searchResults.length === 0) {
    searchResults.push('no results');
  }
  return searchResults;
}
