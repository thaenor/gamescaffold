/**
 * Created by NB21334 / Celfocus 2015.
 *
 * jQuery event handling
 * */

 /**
 * appends html dynamic dates and the number of tickets open.
 * Must only be run once all the ajax requests are completed
 */
 function appendPageElements(){
     $('#startTimeLabel').append(moment().startOf('month').format('MMMM Do YYYY'));
     $('#endTimeLabel').append(moment().format('MMMM Do YYYY'));
     //$('#ticketNumber').empty().append(_openTicketsData.length);
 }


 function renderEvents() {


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
                if(_pagination[_pageTab] >= _maxPageOpenTickets){
                    _pagination[_pageTab]--;
                    $.toaster({ priority : 'warning', title : 'Notice', message : 'no more pages to show'});
                }
                ticketPagination(_openTicketsData);
                break;
            case 'groupLeaderBoard':
                if(_pagination[_pageTab] >= _maxPagePlayerLeaderboard){
                    _pagination[_pageTab]--;
                    $.toaster({ priority : 'warning', title : 'Notice', message : 'no more pages to show'});
                }
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
        if(_pagination[_pageTab] <= 0){
            _pagination[_pageTab]++;
            $.toaster({ priority : 'warning', title : 'Notice', message : 'no more pages to show'});
        }
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


    /** search event handling */
    $("#ticketSearchField").keyup(function () {
        _pagination["ticket"] = 1;
        _pagination["groupLeaderBoard"] = 1;
        updatePageNumber();
        var searchResults = searchTickets($("#ticketSearchField").val());
        if (searchResults[0] == 'no results') {
            $('#ticketList').empty().append("<p class=\"well\">Sorry, these aren't the tickets you are looking for</p>");
        } else {
            ticketPagination(searchResults);
        }
    });


    /** Date pickers for advanced search*/
    $(function () {
        $("#startDatePicker").datepicker({
            dateFormat: "yy-mm-dd"
        });
        $("#endDatePicker").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });


    /** simple validation (if dates are inserted)*/
    $("#startDatePicker, #endDatePicker").change(function () {
        if ($("#startDatePicker").val() && $('#endDatePicker').val()) {
            $('#timeTravelTrigger').removeAttr('disabled');
        }
    });


    /** renewing all ajax calls */
    $("#timeTravelTrigger").click(function () {
        var start = replaceAll('/', '-', $('#startDatePicker').val());
        var end = replaceAll('/', '-', $('#endDatePicker').val());
        _pagination["ticket"] = 1;
        _pagination["groupLeaderBoard"] = 1;
        updatePageNumber();
        getOpenTicketData(start, end);
        var link = generateLink('resolved',start, end);
        getAjaxData(link).done(function(data){
            _resolvedTicketsData = data;
            renderPlayerLeaderBoard(data);
            renderGroupLeaderBoard(data);
            $('#startTimeLabel').empty().append(start);
            $('#endTimeLabel').empty().append(end);
        }).fail(function(){
            $.toaster({ priority : 'danger', title : 'Internal Error', message : 'No closed/resolved tickets received'});
        });
    });


    /** set default time-travel values for last week */
    $("#setTimeWeek").click(function () {
        $("#startDatePicker").val(moment().weekday(-7).format('YYYY[-]M[-]D')); // last Monday
        $('#endDatePicker').val(moment().weekday(-2).format('YYYY[-]M[-]D')); //Last Friday
        $('#timeTravelTrigger').prop('disabled', false);
    });
    /** set default time-travel values for last month */
    $("#setTimeMonth").click(function () {
        $("#startDatePicker").val(moment().subtract(1, 'months').startOf('month').format('YYYY[-]M[-]D')); // last Monday
        $('#endDatePicker').val(moment().subtract(1, 'months').endOf('month').format('YYYY[-]M[-]D')); //Last Friday
        $('#timeTravelTrigger').prop('disabled', false);
    });


    /** Button event to post feed */
    $("#postFeed").click(function(){
        var post = $('#writtenFeed').val();
        if(post){
            //TODO: make ajax call to post feed
            $('#articleList').append('<li class="list-group-item">'+ 'You : ' +post + '</li>');
        } else {
            $.toaster({ priority : 'warning', title : 'Newsfeed', message : 'Please write something'});
        }
    });


    /** Attach a delegated event handler */
    $( "#playerLeaderboard" ).on( "click", "a", function( event ) {
        event.preventDefault();
        $("#playerDetails").empty().append( $(this).text()+'\'s information');
        renderPlayerDetailtModal($(this).text());
    });


    $("#ticketList").on("click","a",function(event){
        event.preventDefault();
        var title = $(this).text();
        $("#ticketDetails").empty().append(title+' related data:');
        var addressValue = $(this).attr("href");
        addressValue = addressValue.replace("#","").trim();
        renderTicketDetailsModal(addressValue);
    });
}
