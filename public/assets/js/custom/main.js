//Celfocus 2015
var _barGraphDesignJson = [];
var _pageTab = "ticket";
var _pagination = [];
_pagination["ticket"] = 1;
_pagination["groupLeaderBoard"] = 1;

var _recPerPage = 10;
var _groupJson;
var _ticketsJson;

function updatePageNumber() {
    $('.pageNumber').empty().append('<i class="glyphicon glyphicon-th-list"></i> Page: ' + _pagination[_pageTab]);
}

$(document).ready(function () {
    var now = new Date();
    var greeting = "Good" + ((now.getHours() > 17) ? " evening." : " day.");
    $('h1').append(' - ' + greeting);
    $('#timeTravelTrigger').prop('disabled', true);

    /*var ticketCall = new TicketsAjaxCall();
    ticketCall.onReady = function () {
        console.log("ticket ajax call onReady. "+Date.now());
        events();
    };
    var groupCall = new GroupsAjaxCall();
    groupCall.onReady = function () {
        //console.log("group ajax call completed.");
    };*/

    getTicketData().done(function(data) {
        _ticketsJson = data;
        console.log(data);
        ticketPagination(_ticketsJson);
        //renderPlayerLeaderBoard(); NOT SUPPOSED TO BE CALLED HERE
        countOpenTickets();
    }).fail(showTicketErrorMessage);

    getGroupMaintData().done(function(data) {
        _groupJson = data;
        //sort groups by points. So highest scoring comes first
        _groupJson.sort(function (a, b) {
            return parseFloat(b.points) -
                parseFloat(a.points)
        });
        leaderBoardPagination(_groupJson);
        drawMorrisBarGraph();
    }).fail(function(){
        console.error('error while making ajax request to retrieve groups json file.');
    });

    Morris.Donut({
        element: 'donut-example',
        data: [
            {label: "Open tickets", value: 12},
            {label: "Resolved tickets", value: 30},
            {label: "In progress", value: 20}
        ]
    });
});
