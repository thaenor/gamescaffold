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
var allArticles;


$(document).ready(function () {
    //$("body > *").not("body > #preloader").hide();
    welcome();
    getOpenTicketData();
    getGroupData();
    getResolvedAndReopenedTicketData();
    //getChallenges();
    getArticles();
    setInterval(function () {
        tabClicker();
    }, 300000);

});

$(document).ajaxStop(function () {
    drawMorrisDonnutChart();
});

/*function getChallenges(){
    var link = generateLink('getChallengesCount');
    getAjaxData(link).done(function(result){
        $('#challengeCount').empty().append(result);
    }).fail(showAlertMessage('failed to get chalenges count'));
}*/

function getArticles(){
    $('#articleList').empty();
    var link = generateLink('articles');
    getAjaxData(link).done(function showArticles(data) {
        allArticles = data;
        displayArticles(data);
    }).fail(showAlertMessage('no articles to show'));
}

function displayArticles(data){
    $.each(data, function (i, currentArticle) {
        $('#articleList').append('<li class="list-group-item">'+currentArticle.author+' : '+ currentArticle.body+'</li>');
    });
}

function updatePageNumber() {
    $('.pageNumber').empty().append('<i class="glyphicon glyphicon-th-list"></i> Page: ' + _pagination[_pageTab]);
}

function welcome(){
    var now = new Date();
    var greeting = "Good" + ((now.getHours() > 17) ? " evening." : " day.");
    $('#welcome').append(greeting + ' - ');
    $('#timeTravelTrigger').prop('disabled', true);
}

function showAlertMessage(message){
    var html = '<div class="alert alert-warning alert-dismissible fade in" role="alert" data-spy="affix" data-offset-top="60" data-offset-bottom="200">'+
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+
        '<strong>Holy guacamole!</strong> '+message+' </div>';
    $('#notificationBox').append(html);
}

function tabClicker(){
    var tabbedArray = ["ticket-tab", "newsfeed-tab", "groupLeaderboard-tab", "player-leaderboard-tab", "graph-tab"];
    var selectedTabIndex = Math.floor((Math.random() * tabbedArray.length));
    $("#" + tabbedArray[selectedTabIndex]).trigger("click");
}