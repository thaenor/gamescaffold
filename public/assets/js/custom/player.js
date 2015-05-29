// player (user) class representation in Javascript
function player(id, title, name, health_points, experience, level, league) {
    this.id = id;
    this.title = title;
    this.name = name;
    this.health_points = health_points;
    this.experience = experience;
    this.level = level;
    this.league = league;
}

/*function drawPlayerLeaderboard(){
  var insertedPlayers = [];
  $('#playerLeaderboard').empty();
  $('#playerLeaderboard').append('<table class="table">');
  $.each(_ticketsJson, function(i, currentPlayer) {
    if()
    $('#playerLeaderboard').append('<tr> <td>' + currentPlayer.user_id + '</td><td>' +currentPlayer.points + '</tr>')
  });
  $('#playerLeaderboard').append('</table>')
}*/
