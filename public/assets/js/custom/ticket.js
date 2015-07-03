/**
 * Created by NB21334 / Celfocus 2015.
 *
 * Ticket related functions
 */

function getOpenTicketData(start,end){
    var link = generateLink('open', start, end);
    getAjaxData(link).done(function(data) {
        _openTicketsData = data;
        ticketPagination(data);
        renderEvents();
    }).fail(showAlertMessage('Getting all the tickets data was a bust!'), showTicketErrorMessage());
}

function getResolvedAndReopenedTicketData(start, end){
    var link = generateLink('resolved',start, end);
    getAjaxData(link).done(function(resolvedData){
        _resolvedTicketsData = resolvedData;
        /*link = generateLink('reOpened');
        getAjaxData(link).done(function(data){
                //TODO: treat scenario where there is no open tickets
                _reopenedTicketsData = data;
        }).fail(showAlertMessage('Getting the reopened tickets was a bad idea... I know'));*/
        renderPlayerLeaderBoard(resolvedData);
        drawMorrisDonnutchart();
    }).fail(showAlertMessage('Getting user score data was a bad idea!'));
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
        if(currentTicket.priority =="1 Critical"){
            $('#ticketList').append('<li class="list-group-item list-group-item-danger"> <a data-toggle="modal" data-target="#ticketModal" href="#">' + currentTicket.title + '</a> <span class="pull-right">' + currentTicket.created_at + '</span> </li>');
        } else if (currentTicket.priority == "2 High") {
            $('#ticketList').append('<li class="list-group-item list-group-item-warning"> <a data-toggle="modal" data-target="#ticketModal" href="#">' + currentTicket.title + '</a> <span class="pull-right">' + currentTicket.created_at + '</span> </li>');
        } else if (currentTicket.priority == "3 Medium") {
            $('#ticketList').append('<li class="list-group-item list-group-item-info"> <a data-toggle="modal" data-target="#ticketModal" href="#">' + currentTicket.title + '</a> <span class="pull-right">' + currentTicket.created_at + '</span> </li>');
        } else{
            $('#ticketList').append('<li class="list-group-item list-group-item-success"> <a data-toggle="modal" data-target="#ticketModal" href="#">' + currentTicket.title + '</a> <span class="pull-right">' + currentTicket.created_at + '</span> </li>');
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
    $.each(_openTicketsData, function (index, currentTicket) {
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

function findTicket(TicketArray, title){
    return TicketArray.filter(function(p) {
        return p.title === title
    });
}


function drawMorrisDonnutchart(openTickets, ResolvedTickets, Pending){
    Morris.Donut({
        element: 'donut-example',
        data: [
            {label: "Open tickets", value: _openTicketsData},
            {label: "Resolved tickets", value: _resolvedTicketsData},
            {label: "In progress", value: _reopenedTicketsData}
        ]
    });
}
