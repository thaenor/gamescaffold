/**
 * Created by NB21334 / Celfocus 2015.
 *
 * Ticket Premier Leader board app
 */
var _barGraphDesignJson = [];
var _pageTab = "ticket";
var _pagination = [];
_pagination["ticket"] = 1;
_pagination["groupLeaderBoard"] = 1;

var _recPerPage = 10;
var _groupJson;
var _openTicketsData;
var _resolvedTicketsData;
var _reopenedTicketsData;


$(document).ready(function () {
    //$("body > *").not("body > #preloader").hide();
    welcome();
    getOpenTicketData();
    getGroupData();
    getResolvedAndReopenedTicketData();
    getChallenges();

    //setTimeout($('#preloader').append('loaded'), 5000);

});

function getChallenges(){
    var link = generateLink('getChallengesCount');
    getAjaxData(link).done(function(result){
        $('#challengeCount').empty().append(result);
    }).fail(showAlertMessage('failed to get chalenges count'));
}

function updatePageNumber() {
    $('.pageNumber').empty().append('<i class="glyphicon glyphicon-th-list"></i> Page: ' + _pagination[_pageTab]);
}

function welcome(){
    var now = new Date();
    var greeting = "Good" + ((now.getHours() > 17) ? " evening." : " day.");
    $('h1').append(' - ' + greeting);
    $('#timeTravelTrigger').prop('disabled', true);
}

function showAlertMessage(message){
    var html = '<div class="alert alert-warning alert-dismissible fade in" role="alert">'+
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+
        '<strong>Holy guacamole!</strong> '+message+' </div>';
    $('#notificationBox').append(html);
}