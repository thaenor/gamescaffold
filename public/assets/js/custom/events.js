/**
 * bellow here there's only event handling
 * */
function events() {
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
                ticketPagination(_ticketsJson);
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
                ticketPagination(_ticketsJson);
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
        var ticketCall = new TicketsAjaxCall(start, end);

        //TODO: make sure this piece of code is run only once ajax call is finished
        ticketCall.onReady = function () {
            //ajax call is made here
            $('#timeTravelTrigger').prop('disabled', true);
            renderGroupLeaderBoard();
            renderPlayerLeaderBoard();
        }
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
