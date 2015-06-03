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
$(document).ready(function() {
  var now = new Date();
  var greeting = "Good" + ((now.getHours() > 17) ? " evening." : " day.");
  $('h1').append(' - '+greeting);
  $('#timeTravelTrigger').prop('disabled', true);
  var ticketCall = new ticketsAjaxCall();
  events();
  var groupCall = new groupsAjaxCall();

  Morris.Donut({
    element: 'donut-example',
    data: [
      {label: "Open tickets", value: 12},
      {label: "Resolved tickets", value: 30},
      {label: "In progress", value: 20}
    ]
  });
});
