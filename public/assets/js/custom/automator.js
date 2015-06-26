/**
 * Created by NB21334 on 26/06/2015.
 */

function automator(){
    var tabbedArray = ["ticket-tab", "newsfeed-tab", "groupLeaderboard-tab", "player-leaderboard-tab", "graph-tab"];

    setTimeout(
        function()
        {
            while(!0){
                var selectedTabIndex = Math.floor((Math.random() * (tabbedArray.length-1)) + 1);
                $("#"+tabbedArray[selectedTabIndex]).find('a').trigger( "click" );
            }
        }, 5000);
}