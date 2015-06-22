/**
 * Created by NB21334 / Celfocus 2015.
 *
 * jQuery event handling
 * */

 function renderEvents() {

    $('#startTimeLabel').append(moment().startOf('month').format('MMMM Do YYYY'));
    $('#endTimeLabel').append(moment().format('MMMM Do YYYY'));
    $('#ticketNumber').empty().append(_openTicketsData.length);
    $('#notificationBox').empty().append('<p></p>');

    /**
     * Click event on next button in pagination
     * This code handles all "next" buttons
     * Please refer to the global variables to
     * see what each one holds
     */
    $(".next").click(function () {
        _pagination[_pageTab]++;
        updatePageNumber();
        switch (_pageTab) {
            case 'ticket':
                ticketPagination(_openTicketsData);
                break;
            case 'groupLeaderBoard':
                _barGraphDesignJson = [];
                leaderBoardPagination(_groupJson);
                drawMorrisBarGraph();
                break;
            default:
                console.error('invalid key in pagination');

        }
    });

    /**
     * Click event on next button in pagination
     *
     */
    $(".previous").click(function () {
        _pagination[_pageTab]--;
        updatePageNumber();
        switch (_pageTab) {
            case 'ticket':
                ticketPagination(_openTicketsData);
                break;
            case 'groupLeaderBoard':
                _barGraphDesignJson = [];
                leaderBoardPagination(_groupJson);
                drawMorrisBarGraph();
                break;
            default:
                console.error('invalid key in pagination');

        }
    });

    $('#ticket-tab').click(function () {
        _pageTab = "ticket";
        updatePageNumber();
    });
    $('#groupLeaderboard-tab').click(function () {
        _pageTab = "groupLeaderBoard";
        updatePageNumber();
    });

    //search event handling
    $("#ticketSearchField").keyup(function () {
        var searchResults = searchTickets($("#ticketSearchField").val());
        if (searchResults[0] == 'no results') {
            $('#ticketList').empty().append("<p class=\"well\">Sorry, these aren't the tickets you are looking for</p>");
        } else {
            ticketPagination(searchResults);
        }
    });


    //Date pickers for advanced search
    $(function () {
        $("#startDatePicker").datepicker({
            dateFormat: "yy-mm-dd"
        });
        $("#endDatePicker").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });

    //simple validation (if dates are inserted)
    $("#startDatePicker, #endDatePicker").change(function () {
        if ($("#startDatePicker").val() && $('#endDatePicker').val()) {
            $('#timeTravelTrigger').removeAttr('disabled');
        }
    });

    //renewing all ajax calls
    $("#timeTravelTrigger").click(function () {
        var start = replaceAll('/', '-', $('#startDatePicker').val());
        var end = replaceAll('/', '-', $('#endDatePicker').val());
        getOpenTicketData(start, end);
        var link = generateLink('resolved',start, end);
        getAjaxData(link).done(function(data){
            _resolvedTicketsData = data;
            renderPlayerLeaderBoard(data);
            renderGroupLeaderBoard(data);
            $('#startTimeLabel').empty().append(start);
            $('#endTimeLabel').empty().append(end);
        }).fail(showAlertMessage('error fetching remote data'));
    });


    $("#setTimeWeek").click(function () {
        $("#startDatePicker").val(moment().weekday(-7).format('YYYY[-]M[-]D')); // last Monday
        $('#endDatePicker').val(moment().weekday(-2).format('YYYY[-]M[-]D')); //Last Friday
        $('#timeTravelTrigger').prop('disabled', false);
    });

    $("#setTimeMonth").click(function () {
        $("#startDatePicker").val(moment().subtract(1, 'months').startOf('month').format('YYYY[-]M[-]D')); // last Monday
        $('#endDatePicker').val(moment().subtract(1, 'months').endOf('month').format('YYYY[-]M[-]D')); //Last Friday
        $('#timeTravelTrigger').prop('disabled', false);
    });

    $("#postFeed").click(function(){
        var post = $('#writtenFeed').val();
        if(post){
            //TODO: make ajax call to post feed
            $('#articleList').append('<li class="list-group-item">'+ 'You : ' +post + '</li>');
        } else {
            showAlertMessage('Please write something before posting to newsfeed');
        }
    });


    // Attach a delegated event handler
    $( "#playerLeaderboard" ).on( "click", "a", function( event ) {
        event.preventDefault();
        $("#playerDetails").empty().append( $(this).text()+'\'s information');
        var player = findPlayers(_resolvedTicketsData,$(this).text());
        var criticalCount= 0, criticalPointCount= 0, highCount= 0, highPointCount= 0, mediumCount= 0, mediumPointCount= 0, lowCount= 0, lowPointCount=0;
        var incidentCount= 0, incidentPointCount= 0, problemCount= 0, problemPointCount= 0, serviceRequestCount= 0, srPointCount= 0;
        $.each(player, function(index,el){
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
            }
        });
        $('#playerList').empty().append('A total of '+player.length+' tickets solved of which <ul>'+
            '<li class="list-group-item list-group-item-danger">' +criticalCount+ ' were P1-Critical <span class="badge">'+criticalPointCount+' Points</span></li>'+
            '<li class="list-group-item list-group-item-warning">'+ highCount + ' were P2 - High <span class="badge">'+highPointCount+' Points</span></li>'+
            '<li class="list-group-item list-group-item-info">'+ mediumCount + ' were P3 - Medium <span class="badge">'+mediumPointCount+' Points</span></li>'+
            '<li class="list-group-item list-group-item-success">'+ lowCount + ' were P1 - Low <span class="badge">'+lowPointCount+' Points</span></li>'+
            '<li class="list-group-item list-group-item-danger">'+ incidentCount + ' were incidents <span class="badge">'+incidentPointCount+' Points</span></li>'+
            '<li class="list-group-item list-group-item-warning">'+ problemCount + ' were P2 - problems <span class="badge">'+problemPointCount+' Points</span></li>'+
            '<li class="list-group-item list-group-item-success">'+ serviceRequestCount + ' were P2 - service requests <span class="badge">'+srPointCount+' Points</span></li>'
        );
        /*$('#playerlist').empty().append('<tr> <td> P1 Critical </td> <td>'+criticalCount+'</td><td>'+criticalPointCount+'</td> </tr>' +
            '<tr> <td> P2 High </td><td>'+highCount+'</td><td>'+highPointCount+'</td></tr>' +
            '<tr> <td> P3 Medium </td><td>'+mediumCount+'</td><td>'+mediumPointCount+'</td></tr>' +
            '<tr> <td> P4 Low </td><td>'+lowCount+'</td><td>'+lowPointCount+'</td></tr>');*/
    });


    $("#ticketList").on("click","a",function(event){
        event.preventDefault();
        var title = $(this).text();
        $("#ticketDetails").empty().append(title+' related data:');
        var ticket = findTicket(_openTicketsData,title);
        $("#ticketInfo").empty().append('<ul class="list-group"><li class="list-group-item"> id: '+ticket[0].id+'</li><li class="list-group-item"> title: '+ticket[0].title+'</li><li class="list-group-item"> priority: '+ticket[0].priority+'</li><li class="list-group-item"> sla: '+ticket[0].sla+'</li><li class="list-group-item"> assigned to: '+ticket[0].user_id+'</li><li class="list-group-item"> team: '+ticket[0].assignedGroup_id+'</li><li class="list-group-item"> points: '+ticket[0].points+'</li><li class="list-group-item"> created at: '+ticket[0].created_at+'</li><li class="list-group-item"> updated at: '+ticket[0].updated_at+'</li></ul>');
    });
}
