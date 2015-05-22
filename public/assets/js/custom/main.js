/**
 * Global variable, always start with underscore
 * _barGraphDesignJson will contain information to draw graph using Morris lib
 * _globalpage the page currently shown for tickets and leaderboard
 * _recPerPage amount of records to display per page (currently static)
 */
var _barGraphDesignJson = [];
var _globalpage = 1;
var _recPerPage = 10;
var _groupJson;
var _ticketsJson;
/**
 * ------------------------------------------------------------------------
 * This contains
 * ------------------------------------------------------------------------
 *
 * -> Ajax request for ticket, group and player information
 * -> Draws the Morris Bar graph
 * -> Logs any ajax errors to the console (if any)
 * -> Next and Previous buttons for pagination
 * -> **Future** Ajax calls
 */
$(document)
    .ready(function() {
$('#timeTravelTrigger').prop('disabled', true);
        var ticketCall = new ticketsAjaxCall();
        ticketCall.onready = function () {
            var groupCall = new groupsAjaxCall();
            groupCall.onready = function () {
                /**
                 * bellow here there's only event handling
                 * -> Pagination functions
                 * -> search bar for tickets
                 * -> Date pickers for advanced search (time travel)
                 * */
                $(".next")
                    .click(function() {
                        _globalpage++;
                        leaderboardPaginator(_groupJson);
                        ticketPaginator(_ticketsJson);
                        drawMorrisBarGraph();
                    });
                $(".previous")
                    .click(function() {
                        _globalpage--;
                        leaderboardPaginator(_groupJson);
                        ticketPaginator(_ticketsJson);
                        drawMorrisBarGraph();
                    });

                    //search event handling
                    $("#ticketSearchField")
                        .change(function() {
                            var searchResults = searchTickets($("#ticketSearchField").val());
                            if(searchResults[0] == 'no results'){
                                $('#ticketList').empty();
                                $('#ticketList').append('<p class="well">Sorry, these aren\'t the tickets you are looking for</p>');
                            }else {
                                ticketPaginator(searchResults);
                            }
                        });

                    //Date pickers for advanced search
                    $(function() {
                        $( "#startDatepicker" ).datepicker();
                        $( "#endDatepicker" ).datepicker();
                      });
                    //simple validation (if dates are inserted)
                      $("#startDatepicker, #endDatepicker").change(function(field){
                        if($("#startDatepicker").val() && $('#endDatepicker').val()){
                            $('#timeTravelTrigger').removeAttr('disabled');
                        }
                      });
                    //renewing all ajax calls
                    $("#timeTravelTrigger")
                        .click(function() {
                          var start = replaceAll('/','-',$('#startDatepicker').val());
                          var end = replaceAll('/','-',$('#endDatepicker').val());
                              var ticketCall = new ticketsAjaxCall(start, end);
                              ticketCall.onready = function () {
                                  //ajax call is made here
                                  $('#timeTravelTrigger').prop('disabled', true);
                              }
                        });
            }
        };

    });


function replaceAll(find, replace, str) {
  return str.replace(new RegExp(find, 'g'), replace);
}


function groupsAjaxCall() {
    this.onready = function () {}; // Our onready function
    this.response = {}; // The response Variable
    var self = this; // "References" this scope and all the "this" variables

     $.ajax({
        url: '/api/v1/groups',
        dataType: 'json',
        success: function(json) {
            self.response = json; // Sets the response
            self.onready.apply(self); // Calls the callback

            // get the `groups` array
            _groupJson = json;
            //sort groups by points. So highest scoring comes first
            _groupJson.sort(function(a, b) {
                return parseFloat(b.points) -
                    parseFloat(a.points)
            });

            _globalpage = 1;
            leaderboardPaginator(_groupJson);
            drawMorrisBarGraph();
        },
        error: function() {
                console.error(
                    'error while making ajax request to retrieve groups json file. If you are a dev I\'m from main.js'
                );
            }
    });
}


function ticketsAjaxCall (start, end) {
    this.onready = function () {}; // Our onready function
    this.response = {}; // The response Variable
    var self = this; // "References" this scope and all the "this" variables

    var link;
    if(start && end){ link ='/api/v1/tickets/'+start+'&'+end; console.log(link);}
    else { link = '/api/v1/tickets/'; }

    $.ajax({
        url: link,
        dataType: 'json',
        success: function(json) {
            self.response = json; // Sets the response
            self.onready.apply(self); // Calls the callback
            if(json.length != 0){
                _ticketsJson = json;
                ticketPaginator(_ticketsJson);
            }else{
                showTicketErrorMessage();
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError){
            if(xhr.status==404) {
                console.error('404 Error - the date referenced a future time or was incorrect ' + thrownError);
            } else if(xhr.status==400) {
                console.error('400 Error - the dates supplied were poorly formated. Input valid dates (let jQueryUI do it\'s work) ' + thrownError);
            } else { console.error('unknown error: '+thrownError); }
            showTicketErrorMessage();
        }
    });
}


function showTicketErrorMessage(){
    $('#ticketList').empty().append('<div class="alert alert-danger" role="alert">Sorry, these aren\'t the tickets you are looking for...</div>');
}

/**
 * Draws a page in the leaderboard. Currently this displays _recPerPage records per page
 */
function leaderboardPaginator(groups) {

    /**
     * This should work.
     * The children() function returns a JQuery object that contains the children.
     * So you just need to check the size and see if it has at least one child.
     * #grouplist > * makes it slightly faster - I think...
     */
    if ($('#grouplist > *')
        .length > 0) {
        $.each($('#grouplist')
            .children(), function(i, current) {
                current.remove();
            });
    }
    /*Or you could just do $('#grouplist').empty()*/

    var page = _globalpage,
        startRec = Math.max(page - 1, 0) * _recPerPage,
        endRec = Math.min(startRec + _recPerPage, groups.length)
    recordsToShow = groups.slice(startRec, endRec);

    // loop through the array to populate your list
    $.each(recordsToShow, function(i, currentGroup) {
        // alternative - output data has a list. adds an option tag to your existing list
        //$('#yourlist').append(new Option( currentAirport.airport_name )); adds option tags with item
        //$('#grouplist').append('<li>'+ '<a href="#profile"" data-toggle="tab">'+ currentGroup.title + '</a>' +'</li>'); print names in list
        //draws has table. Column "variant name" is hidden on smaller screens
        $('#grouplist')
            .append('<tr> <td class="success">' + currentGroup.title +
                '</td>' + '<td class="info hidden-xs hidden-sm">' +
                currentGroup.variant_name + '</td>' +
                '<td class="warning">' + currentGroup.points +
                '</td> </tr>');

        fillBarGraphData(currentGroup.title, currentGroup.points);
    });
}

/**
 * Draws a page in the tickets tab. Currently this displays _recPerPage records per page.
 * Both this and leaderboard page are synced. This means that clicking next will change to page 2 globally.
 * Not sure to view that as a feature or a bug.
 */
function ticketPaginator(tickets) {
    $('#ticketList')
        .empty();
    var page = _globalpage,
        startRec = Math.max(page - 1, 0) * _recPerPage,
        endRec = Math.min(startRec + _recPerPage, tickets.length)
    recordsToShow = tickets.slice(startRec, endRec);
    $.each(recordsToShow, function(i, currentTicket) {
        if (currentTicket.priority == "2 High") {
            $('#ticketList')
                .append(
                    '<li class="list-group-item list-group-item-danger"> <a href="#">' +
                    currentTicket.title + '</a> </li>');
        } else if (currentTicket.priority == "3 Medium") {
            $('#ticketList')
                .append(
                    '<li class="list-group-item list-group-item-warning"> <a href="#">' +
                    currentTicket.title + '</a> </li>');
        } else {
            $('#ticketList')
                .append(
                    '<li class="list-group-item list-group-item-info"> <a href="#">' +
                    currentTicket.title + '</a> </li>');
        }
    });
}

/**
 * (Re)draws Morris bar graph displaying all teams.
 * Each time a new page is opened the old graphs aren't removed, to do that you'd have to either remove them
 * Or supply a copy of _barGraphDesignJson with only the desired data
 * */
function drawMorrisBarGraph() {
    $('#morris-bar-chart')
        .empty();
    Morris.Bar({
        element: 'morris-bar-chart',
        data: _barGraphDesignJson,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Group name']
    });
}

/**
 * Fills global array @_barGraphDesignJson with name and points of each group.
 * This array will later be used by Morris lib to draw the graph
 */
function fillBarGraphData(title, points) {
    var tmp = {};
    tmp.y = title;
    tmp.a = points;
    _barGraphDesignJson.push(tmp);
}


/**
 * Searches the ticket array for the contents of
 * the string received as parameter.
 * if no results are found the single element
 * "no results" is returned
 */
function searchTickets(searchString){
    var searchResults = [];
    $.each(_ticketsJson, function(index, currentTicket) {
        if(currentTicket.title.includes(searchString)){
            searchResults.push(currentTicket);
        }
    });

    if(searchResults.length === 0){
        searchResults.push('no results');
    }
    return searchResults;
}


/**
 * The var_dump(variable) equivalent in Javascript
 * dumps all the information about a variable to the console.
 */
function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }
    console.log(out);
}

/* PORTABLE CHEATSHEET
        $("#test").hide();          Selects tag with hi test
        $(".test")                  Selects all tags with class test
        $("*")                      Selects all elements
        $(this)                     Selects the current HTML element
        $("p.intro")                Selects all <p> elements with class="intro"
        $("p:first")                Selects the first <p> element
        $("ul li:first")            Selects the first <li> element of the first <ul>
        $("ul li:first-child")      Selects the first <li> element of every <ul>
        $("[href]")                 Selects all elements with an href attribute
        $("a[target='_blank']")     Selects all <a> elements with a target attribute value equal to "_blank"
        $("a[target!='_blank']")    Selects all <a> elements with a target attribute value NOT equal to "_blank"
        $(":button")                Selects all <button> elements and <input> elements of type="button"
        $("tr:even")                Selects all even <tr> elements
        $("tr:odd")                 Selects all odd <tr> elements

        Action event
        $("p").click(function(){ // action goes here!! });

        $("div").find("span");

*/
