/**
 * Created by NB21334 / Celfocus 2015.
 *
 * Ticket related functions
 */

function parseLink(start, end){
    var link = '/api/v1/tickets/';
    if (start && end) {
        link = link + start + '&' + end;
    }
    return link;
}

function getTicketData(start, end) {
    var link = parseLink(start,end);
    return $.ajax({
        url : link,
        dataType: 'json'
    });
}



function countOpenTickets(){
    $('#ticketNumber').empty().append(_ticketsJson.length);
}

function replaceAll(find, replace, str) {
    return str.replace(new RegExp(find, 'g'), replace);
}


function showTicketErrorMessage() {
    $('#ticketList').empty().append('<div class="alert alert-danger" role="alert">Something went wrong... these aren\'t the tickets you are looking for...</div>');
}


/**
 * Draws a page in the tickets tab. Currently this displays _recPerPage records per page.
 * Both this and leaderBoard page are synced. This means that clicking next will change to page 2 globally.
 * Not sure to view that as a feature or a bug.
 */
function ticketPagination(tickets) {
    $('#ticketList').empty();
    var page = _pagination[_pageTab],
        startRec = Math.max(page - 1, 0) * _recPerPage,
        endRec = Math.min(startRec + _recPerPage, tickets.length);
    var recordsToShow = tickets.slice(startRec, endRec);
    $.each(recordsToShow, function (i, currentTicket) {
        if (currentTicket.priority == "2 High") {
            $('#ticketList').append('<li class="list-group-item list-group-item-danger"> <a href="#">' + currentTicket.title + '<span class="pull-right">' + currentTicket.created_at + '</span> </a></li>');
        } else if (currentTicket.priority == "3 Medium") {
            $('#ticketList').append('<li class="list-group-item list-group-item-warning"> <a href="#">' + currentTicket.title + '<span class="pull-right">' + currentTicket.created_at + '</span> </a></li>');
        } else {
            $('#ticketList').append('<li class="list-group-item list-group-item-info"> <a href="#">' + currentTicket.title + '<span class="pull-right">' + currentTicket.created_at + '</span> </a></li>');
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
    $.each(_ticketsJson, function (index, currentTicket) {
        //TODO: rewrite this using indexOf
        if (currentTicket.title.includes(searchString)) {
            searchResults.push(currentTicket);
        }
    });

    if (searchResults.length === 0) {
        searchResults.push('no results');
    }
    return searchResults;
}
