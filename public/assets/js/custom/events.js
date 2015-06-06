/**
 * Created by NB21334 / Celfocus 2015.
 *
 * jQuery event handling
 * */

 function renderEvents() {

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
    $("#ticketSearchField").change(function () {
        var searchResults = searchTickets($("#ticketSearchField").val());
        if (searchResults[0] == 'no results') {
            $('#ticketList').empty().append("<p class=\"well\">Sorry, these aren't the tickets you are looking for</p>");
        } else {
            ticketPagination(searchResults);
        }
    });


    //Date pickers for advanced search
    $(function () {
        $("#startDatePicker").datepicker();
        $("#endDatePicker").datepicker();
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
        var link = generateLink('open',start,end);
        //TODO: create ReCalculatePage function to redraw all the tables

    });


    $("#setTimeWeek").click(function () {
        $("#startDatePicker").val(moment().weekday(-7).format('M[/]D[/]YYYY')); // last Monday
        $('#endDatePicker').val(moment().weekday(-2).format('M[/]D[/]YYYY')); //Last Friday
        $('#timeTravelTrigger').prop('disabled', false);
    });

    $("#setTimeMonth").click(function () {
        $("#startDatePicker").val(moment().subtract(1, 'months').startOf('month').format('M[/]D[/]YYYY')); // last Monday
        $('#endDatePicker').val(moment().subtract(1, 'months').endOf('month').format('M[/]D[/]YYYY')); //Last Friday
        $('#timeTravelTrigger').prop('disabled', false);
    });

}
