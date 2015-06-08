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
            console.log(data);
            renderPlayerLeaderBoard(data);
            renderGroupLeaderBoard(data);
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
}
