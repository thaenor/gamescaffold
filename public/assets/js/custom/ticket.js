/**
 * Created by NB21334 / Celfocus 2015.
 *
 * Ticket related functions
 */

function getOpenTicketData(start,end){
    var link = generateLink('open', start, end);
    getAjaxData(link).done(function(data) {
        _openTicketsData = data;
        _openTicketsData.sort(sort_by('priority',false,function(a){return a.toUpperCase()}));
        ticketPagination(data);
    }).fail(function(){
        $.toaster({ priority : 'danger', title : 'Tickets', message : 'No open tickets to show.'})
    });
}

function getResolvedAndReopenedTicketData(start, end){
    var link = generateLink('resolved',start, end);
    getAjaxData(link).done(function(resolvedData){
        _resolvedTicketsData = resolvedData;
        /*link = generateLink('reOpened');
        getAjaxData(link).done(function(data){
                _reopenedTicketsData = data;
        }).fail(showAlertMessage('Getting the reopened tickets was a bad idea... I know'));*/
        renderPlayerLeaderBoard(resolvedData);
        //drawMorrisDonnutchart();
    }).fail(function(){
        $.toaster({ priority : 'warning', title : 'Leaderboard', message : 'We lost our leaderboard calculator, sorry!'});
    });
}


function replaceAll(find, replace, str) {
    return str.replace(new RegExp(find, 'g'), replace);
}


var sort_by = function(field, reverse, primer){

    var key = primer ?
        function(x) {return primer(x[field])} :
        function(x) {return x[field]};

    reverse = !reverse ? 1 : -1;

    return function (a, b) {
        return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
    }
};
/*
 // Sort by city, case-insensitive, A-Z
 homes.sort(sort_by('city', false, function(a){return a.toUpperCase()}));
* */

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
    if(recordsToShow.length <= 0){
        $.toaster({ priority : 'warning', title : 'No tickets to show', message : ''});
        return;
    }
    $.each(recordsToShow, function (i, currentTicket) {
        if(currentTicket.priority =="1 Critical"){
            $('#ticketList').append('<li class="list-group-item list-group-item-danger"> <a data-toggle="modal" data-target="#ticketModal" href="#'+currentTicket.id+'">' + currentTicket.title + '</a> <span class="pull-right">' + currentTicket.created_at + '</span> <span class="badge">'+currentTicket.percentage+'%</span> </li>');
        } else if (currentTicket.priority == "2 High") {
            $('#ticketList').append('<li class="list-group-item list-group-item-warning"> <a data-toggle="modal" data-target="#ticketModal" href="#'+currentTicket.id+'">' + currentTicket.title + '</a> <span class="pull-right">' + currentTicket.created_at + '</span> <span class="badge">'+currentTicket.percentage+'%</span> </li>');
        } else if (currentTicket.priority == "3 Medium") {
            $('#ticketList').append('<li class="list-group-item list-group-item-info"> <a data-toggle="modal" data-target="#ticketModal"href="#'+currentTicket.id+'">' + currentTicket.title + '</a> <span class="pull-right">' + currentTicket.created_at + '</span> <span class="badge">'+currentTicket.percentage+'%</span> </li>');
        } else{
            $('#ticketList').append('<li class="list-group-item list-group-item-success"> <a data-toggle="modal" data-target="#ticketModal" href="#'+currentTicket.id+'">' + currentTicket.title + '</a> <span class="pull-right">' + currentTicket.created_at + '</span> <span class="badge">'+currentTicket.percentage+'%</span> </li>');
        }
    });
}


/**
 * Searches the ticket array for the contents of
 * the string received as parameter.
 * if no results are found the single element
 * "no results" is returned
 * this method is only functional on some browsers,
 * to optimize this, use the array.prototype.indexOf()
 * @param searchString - string to be searched
 */
function searchTickets(searchString) {
    var searchResults = [];
    $.each(_openTicketsData, function (index, currentTicket) {
        var lowerCaseTitle = currentTicket.title.toLowerCase();
        searchString.toLowerCase();
        if (lowerCaseTitle.indexOf(searchString) != -1) {
            searchResults.push(currentTicket);
        }
    });

    if (searchResults.length === 0) {
        searchResults.push('no results');
    }
    return searchResults;
}

function findTicket(ticketArray, ticketId){
    for(var i=0; i<ticketArray.length; i++){
        if(ticketArray[i].id === id){
            return ticketArray[i];
        }
    }
    return false;
}


function drawMorrisDonnutChart(){
    Morris.Donut({
        element: 'donut-example',
        data: [
            {label: "Open tickets", value: _openTicketsData.length},
            {label: "Resolved tickets", value: _resolvedTicketsData.length},
            {label: "In progress", value: _openTicketsData.length}
        ]
    });
}

function renderPlayerDetailtModal(playerName){
    var ticketsOwnedByPlayer = findPlayers(_resolvedTicketsData,playerName);
    var criticalCount= 0, criticalPointCount= 0, highCount= 0, highPointCount= 0, mediumCount= 0, mediumPointCount= 0, lowCount= 0, lowPointCount=0;
    var incidentCount= 0, incidentPointCount= 0, problemCount= 0, problemPointCount= 0, serviceRequestCount= 0, srPointCount= 0, slaPenalty=0, slaOutput = "";
    if(ticketsOwnedByPlayer.length <= 0){
        $.toaster({ priority : 'warning', title : 'No players to show', message : ''});
        return;
    }
    $.each(ticketsOwnedByPlayer, function(index,el){
        switch (el.priority){
            case '1 Critical':
                criticalCount++;
                criticalPointCount += 10;
                break;
            case '2 High':
                highCount++;
                highPointCount += 8;
                break;
            case '3 Medium':
                mediumCount++;
                mediumPointCount += 3;
                break;
            case '4 Low':
                lowCount++;
                lowPointCount += 1;
                break;
            default:
                console.log("Warning: default in priority switch case. This is not fatal");
                break;
        }
        switch(el.type) {
            case "Incident":
                incidentCount++;
                incidentPointCount += 10;
                break;
            case "Service Request":
                serviceRequestCount++;
                srPointCount += 3;
                break;
            case "Problem":
                problemCount++;
                problemPointCount += 5;
                break;
            default:
                console.log("Warning: default in type switch case. This is not fatal");
                break;
        }

        if(el.percentage > 40) {
            if (el.percentage <= 100) {
                slaPenalty = ( el.points * (el.percentage / 100) );
                slaPenalty = Math.ceil(slaPenalty);
                slaOutput += "<li class='list-group-item list-group-item-warning'> <em> the ticket <abbr title='"+el.title+"'>" +el.id+ "</abbr> is <abbr title='means the sla is getting big'>growing mold</abbr> </em> - "+el.percentage+"% <span class='badge'>"+slaPenalty+" Points can still be earned!</span> </li>";
            }
            if (el.percentage > 100) {
                slaPenalty = el.points - (el.points * (el.percentage / 100));
                slaPenalty = Math.floor(slaPenalty);
                slaOutput = "<li class='list-group-item list-group-item-danger'> <em> the ticket <abbr title='"+el.title+"'>" +el.id+ "</abbr> <abbr title='sla went KAPUT!'> blew up!1! </abbr> </em> - "+el.percentage+"% <span class='badge'>"+slaPenalty+" Points Lost</span> </li>";
            }
        } /*else {
            slaOutput += "<li class='list-group-item list-group-item-info'> ticket is <abbr title='means the sla is still small'>primed</abbr> - "+el.percentage+"% </li>";
        }*/
    });
    $('#playerList').empty().append('A total of '+ticketsOwnedByPlayer.length+' tickets solved of which <ul>'+
        '<li class="list-group-item"> <u> point analysis based on priority </u> </li>'+
        '<li class="list-group-item list-group-item-danger">' +criticalCount+ ' were P1-Critical <span class="badge">'+criticalPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-warning">'+ highCount + ' were P2 - High <span class="badge">'+highPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-info">'+ mediumCount + ' were P3 - Medium <span class="badge">'+mediumPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-success">'+ lowCount + ' were P1 - Low <span class="badge">'+lowPointCount+' Points</span></li>'+
        '<li class="list-group-item"> <u> point analysis based on type </u> </li>'+
        '<li class="list-group-item list-group-item-danger">'+ incidentCount + ' were incidents <span class="badge">'+incidentPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-warning">'+ problemCount + ' were problems <span class="badge">'+problemPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-success">'+ serviceRequestCount + ' were service requests <span class="badge">'+srPointCount+' Points</span></li> ' +
        '<li class="list-group-item"> <u>warnings</u> </li>'+
        slaOutput+'</ul>'
    );

    /*$('#playerlist').empty().append('<tr> <td> P1 Critical </td> <td>'+criticalCount+'</td><td>'+criticalPointCount+'</td> </tr>' +
     '<tr> <td> P2 High </td><td>'+highCount+'</td><td>'+highPointCount+'</td></tr>' +
     '<tr> <td> P3 Medium </td><td>'+mediumCount+'</td><td>'+mediumPointCount+'</td></tr>' +
     '<tr> <td> P4 Low </td><td>'+lowCount+'</td><td>'+lowPointCount+'</td></tr>');*/
}

function renderTeamDetailModal(teamName){
    var ticketsOwnedByTeam = findTeamTickets(_resolvedTicketsData,teamName);
    var criticalCount= 0, criticalPointCount= 0, highCount= 0, highPointCount= 0, mediumCount= 0, mediumPointCount= 0, lowCount= 0, lowPointCount=0;
    var incidentCount= 0, incidentPointCount= 0, problemCount= 0, problemPointCount= 0, serviceRequestCount= 0, srPointCount= 0, slaPenalty=0, slaOutput = "";
    if(ticketsOwnedByTeam.length <= 0){
        $.toaster({ priority : 'warning', title : 'No team data to show', message : ''});
        return;
    }
    $.each(ticketsOwnedByTeam, function(index,el){
        switch (el.priority){
            case '1 Critical':
                criticalCount++;
                criticalPointCount += 10;
                break;
            case '2 High':
                highCount++;
                highPointCount += 8;
                break;
            case '3 Medium':
                mediumCount++;
                mediumPointCount += 3;
                break;
            case '4 Low':
                lowCount++;
                lowPointCount += 1;
                break;
            default:
                console.log("Warning: default in priority switch case. This is not fatal");
                break;
        }
        switch(el.type) {
            case "Incident":
                incidentCount++;
                incidentPointCount += 10;
                break;
            case "Service Request":
                serviceRequestCount++;
                srPointCount += 3;
                break;
            case "Problem":
                problemCount++;
                problemPointCount += 5;
                break;
            default:
                console.log("Warning: default in type switch case. This is not fatal");
                break;
        }

        if(el.percentage > 40) {
            if (el.percentage <= 100) {
                slaPenalty = ( el.points * (el.percentage / 100) );
                slaPenalty = Math.ceil(slaPenalty);
                slaOutput += "<li class='list-group-item list-group-item-warning'> <em> the ticket <abbr title='"+el.title+"'>" +el.id+ "</abbr> is <abbr title='means the sla is getting big'>growing mold</abbr> </em> - "+el.percentage+"% <span class='badge'>"+slaPenalty+" Points can still be earned!</span> </li>";
            }
            if (el.percentage > 100) {
                slaPenalty = el.points - (el.points * (el.percentage / 100));
                slaPenalty = Math.floor(slaPenalty);
                slaOutput = "<li class='list-group-item list-group-item-danger'> <em> the ticket <abbr title='"+el.title+"'>" +el.id+ "</abbr> <abbr title='sla went KAPUT!'> blew up!1! </abbr> </em> - "+el.percentage+"% <span class='badge'>"+slaPenalty+" Points Lost</span> </li>";
            }
        } /*else {
         slaOutput += "<li class='list-group-item list-group-item-info'> ticket is <abbr title='means the sla is still small'>primed</abbr> - "+el.percentage+"% </li>";
         }*/
    });
    $('#teamList').empty().append('A total of '+ticketsOwnedByTeam.length+' tickets solved of which <ul>'+
        '<li class="list-group-item"> <u> point analysis based on priority </u> </li>'+
        '<li class="list-group-item list-group-item-danger">' +criticalCount+ ' were P1-Critical <span class="badge">'+criticalPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-warning">'+ highCount + ' were P2 - High <span class="badge">'+highPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-info">'+ mediumCount + ' were P3 - Medium <span class="badge">'+mediumPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-success">'+ lowCount + ' were P1 - Low <span class="badge">'+lowPointCount+' Points</span></li>'+
        '<li class="list-group-item"> <u> point analysis based on type </u> </li>'+
        '<li class="list-group-item list-group-item-danger">'+ incidentCount + ' were incidents <span class="badge">'+incidentPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-warning">'+ problemCount + ' were problems <span class="badge">'+problemPointCount+' Points</span></li>'+
        '<li class="list-group-item list-group-item-success">'+ serviceRequestCount + ' were service requests <span class="badge">'+srPointCount+' Points</span></li> ' +
        '<li class="list-group-item"> <u>warnings</u> </li>'+
        slaOutput+'</ul>'
    );

    /*$('#playerlist').empty().append('<tr> <td> P1 Critical </td> <td>'+criticalCount+'</td><td>'+criticalPointCount+'</td> </tr>' +
     '<tr> <td> P2 High </td><td>'+highCount+'</td><td>'+highPointCount+'</td></tr>' +
     '<tr> <td> P3 Medium </td><td>'+mediumCount+'</td><td>'+mediumPointCount+'</td></tr>' +
     '<tr> <td> P4 Low </td><td>'+lowCount+'</td><td>'+lowPointCount+'</td></tr>');*/
}


function displayTicketPercentage(percentage){
    var output = "";
    if(percentage > 100){
        output = "WARNING: expired "+percentage;
    } else if(percentage < 20){
        output  = "early ticket "+percentage;
    } else {
        output = percentage;
    }
    return output;
}

function renderTicketDetailsModal(ticketId){
    var ticket = findTicket(_openTicketsData,ticketId);
    var timeToSolveInDays = parseInt(ticket.sla_time)/1440;
    if( ticket != false){
        $("#ticketInfo").empty().append('<ul class="list-group">' +
        '<li class="list-group-item"> id: #'+ticket.id+'</li>' +
        '<li class="list-group-item"> title: '+ticket.title+'</li>' +
        '<li class="list-group-item"> status: '+ticket.state+'</li>'+
        '<li class="list-group-item"> <b> type: '+ticket.type+'</b> </li>' +
        '<li class="list-group-item"> <b> priority: '+ticket.priority+'</b> </li>' +
        '<li class="list-group-item"> <b> sla: '+ticket.sla+' </b> <span class="badge">'+displayTicketPercentage(ticket.percentage)+'%</span> </li>' +
        '<li class="list-group-item"> <b> assigned to: '+ticket.user_id+' </b> </li>' +
        '<li class="list-group-item"> total time to solve: '+timeToSolveInDays+' days ( '+ticket.sla_time+' minutes) </li>' +
        '<li class="list-group-item"> team: '+ticket.assignedGroup_id+'</li>' +
        '<li class="list-group-item"> points: '+ticket.points+'</li>' +
        '<li class="list-group-item"> created at: '+ticket.created_at+'</li>' +
        '<li class="list-group-item"> updated at: '+ticket.updated_at+'</li>' +
        '<li class="list-group-item"> external ID: '+ticket.external_id+'</li>' +
        '</ul>');
    } else{
        $("#ticketInfo").empty().append('No ticket found');
    }

}