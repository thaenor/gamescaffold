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
  $('#timeTravelTrigger').prop('disabled', true);
  var ticketCall = new ticketsAjaxCall();
  ticketCall.onready = function() {
    events();
  };
  var groupCall = new groupsAjaxCall();
  groupCall.onready = function() {  }

});
