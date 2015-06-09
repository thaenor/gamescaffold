<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta content="IE=edge" http-equiv="X-UA-Compatible">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="yes" name="apple-mobile-web-app-capable">

  <title>Celfocus Gamification</title>
  <!-- includes at head-->
  <link href="assets/css/animations.css" rel="stylesheet">
  <!-- Bootstrap jquery Styles-->
  <link href="assets/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
  <link href="assets/jquery-ui-1.11.4/jquery-ui.theme.css" rel="stylesheet">
  <link href="assets/bootstrap-3.3.4/css/bootstrap.min.css" rel="stylesheet">
  <!-- Big Video -->
  <!-- <link href="assets/BigVideo/css/bigvideo.css"/> -->
  <!-- FontAwesome Styles-->
  <link href="assets/css/font-awesome.css" rel="stylesheet">
  <!-- Morris Chart Styles-->
  <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet">
  <!-- Custom Styles-->
  <link href="assets/css/custom-styles.css" rel="stylesheet">
  <!-- Google Fonts-->
  <!--<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>-->
    <link href='assets/css/font/font.css' rel='stylesheet' type='text/css'>
  <!-- outdated browser pluggin-->
  <link href="assets/css/outdatedbrowser.min.css" rel="stylesheet">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
  <div id="outdated">
  </div>
<!--  <div id="preloader">
  		<div id="status">loading</div>
  	</div>
-->
<div class="col-sm-12 col-md-12 col-lg-12">
  <img src="assets/logo.png" class=""/>
  <h1>Tickets Premier League </h1>
</div>

<div id="notificationBox" class="col-md-5 col-sm-6 col-lg-6 col-md-offset-2 col-lg-offset-5 col-sm-offset-2">

</div>

 <section class="col-md-12 col-lg-12">
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
              <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
              <li data-target="#carousel-example-generic" data-slide-to="1"></li>
              <li data-target="#carousel-example-generic" data-slide-to="2"></li>
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
              <div class="item active">

                  <div class="carousel-caption">
                      SomeGraph
                  </div>
              </div>
              <div class="item">

                  <div class="carousel-caption">
                      CompanyLogo
                  </div>
              </div>
          </div>

          <!-- Controls -->
          <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
          </a>
      </div>
</section>

  <div class="col-md-6 col-sm-6 col-lg-6">
    <div class="panel panel-default">
      <div class="panel-heading">

      </div>


      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li id="ticket-tab" class="active">
            <a data-toggle="tab" href="#ticketDisplayScreen">Tickets</a>
          </li>


          <li class="">
            <a data-toggle="tab" href="#rewards">Rewards</a>
          </li>


          <li class="">
            <a data-toggle="tab" href="#newsfeed">Newsfeed</a>
          </li>


          <li id="groupLeaderboard-tab" class="">
            <a data-toggle="tab" href="#Gleaderboard">Group Leaderboard</a>
          </li>

          <li class="">
            <a data-toggle="tab" href="#Pleaderboard">Player Leaderboard</a>
          </li>
        </ul>

        <hr/>

        <div class="tab-content">
          <div class="tab-pane fade active in" id="ticketDisplayScreen">
            <h4>Tickets</h4>


            <nav class="col-md-12 col-sm-12 col-lg-12">
              <ul class="pager">
                <li class="previous">
                  <a href="#">
                    <span>&larr;</span> Previous</a>
                </li>

                <li>
                  <a class="pageNumber" href="#">
                    <i class="glyphicon glyphicon-th-list"></i> Page: 1</a>
                </li>


                <li class="next">
                  <a href="#">Next <span>&rarr;</span></a>
                </li>
              </ul>
            </nav>

            <!-- search bar -->
            <div class="col-md-6 col-sm-12 col-lg-6">
              <div class="input-group">
                <div class="input-group">
                  <span class="input-group-addon glyphicon glyphicon-search" id="sizing-addon1"></span>
                  <input class="form-control" id="ticketSearchField" placeholder="Search for..." type="text">
                </div>
              </div> <!-- /input-group -->
            </div>
            <!-- END search bar -->

            <hr/>

            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12 hatch">
                <ul class="list-group pullUp" id="ticketList">
                  <!-- LIST WITH TICKETS -->
                </ul>
              </div>
            </div>
          </div>


          <div class="tab-pane fade well" id="rewards">
            <h4>Rewards</h4>
            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          </div>


          <div class="tab-pane fade well" id="newsfeed">
            <h4>Newsfeed</h4>

            <section>
                  <div class='form-group'>
                    <div class="col-md-12 col-sm-12 col-lg-12">
                      <ul id="articleList" class="list-group">
                        <li class="list-group-item">Jason: Cras justo odio</li>
                        <li class="list-group-item">Mark: Dapibus ac facilisis in</li>
                        <li class="list-group-item">Tony: Morbi leo risus</li>
                        <li class="list-group-item">Martha: Porta ac consectetur ac</li>
                        <li class="list-group-item">Xana: Vestibulum at eros</li>
                      </ul>
                    </div>
                    <input type="text" class="form-control" id="writtenFeed" placeholder="What's on your mind...">

                  </div>
                  <button type="submit" id="postFeed" class="btn btn-info">Post</button>

            </section>



          </div>


          <div class="tab-pane fade" id="Gleaderboard">
            <h4>Leaderboard</h4>
            <!-- group leaderboard -->

            <div class="col-md-12">
              <!--    Context Classes  -->


              <div class="panel panel-default">
                <div class="panel-heading" id="leaderboard">
                  Group leaderboard
                </div>


                <nav>
                  <ul class="pager">
                    <li class="previous">
                      <a href="#">
                        <span>&larr;</span>
                        Previous</a>
                    </li>

                    <li>
                      <a class="pageNumber" href="#">
                        <i class="glyphicon glyphicon-th-list"></i> Page: 1</a>
                    </li>

                    <li class="next">
                      <a href="#">Next <span>&rarr;</span></a>
                    </li>
                  </ul>
                </nav>


                <div class="panel-body">
                  <div id="table-resp" class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th class="hidden-xs hidden-sm">Description</th>
                          <th>Points</th>
                        </tr>
                      </thead>


                      <tbody id='grouplist'></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!--  end  Context Classes  -->
            </div>
            <!-- END group leaderboard -->
          </div>

          <div class="tab-pane fade" id="Pleaderboard">
            <div class="panel-heading">
              Player leaderboard
            </div>
              <div class="table-responsive">
                  <table class="table">
                      <thead>
                      <tr>
                          <th>Name</th>
                          <th>Points</th>
                      </tr>
                      </thead>

                      <tbody id='playerLeaderboard'></tbody>
                  </table>
              </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="well col-md-6 col-sm-6 col-lg-6 ">

    <section class="col-md-12 col-sm-12 col-xs-12 hatch">
      <div class="col-md-3 col-sm-6 col-xs-6">
			     <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-red set-icon">
                    <i class="fa fa-envelope-o"></i>
                </span>
                <div class="text-box">
                    <p id="ticketNumber" class="main-text"></p>
                    <p class="text-muted">tickets are open and waiting</p>
                </div>
           </div>
	     </div>

       <div class="col-md-3 col-sm-6 col-xs-6">
            <div class="panel panel-back noti-box">
                 <span class="icon-box bg-color-red set-icon">
                   <i class="glyphicon glyphicon-user"></i>
                 </span>
                 <div class="text-box">
                     <p id="playersInLeague" class="main-text"></p>
                     <p class="text-muted">Players in your league</p>
                 </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-6">
           <div class="panel panel-back noti-box">
                <span class="icon-box bg-color-brown set-icon">
                    <i class="fa fa-rocket"></i>
                </span>
                <div class="text-box">
                    <p id="challengeCount" class="main-text">3</p>
                    <p class="text-muted">Challenges open Solve them now!</p>
                </div>
             </div>
		     </div>
    </section>

    <hr/>
    <ul class="list-inline">
      <li>
        <a href="http://localhost/users"><i class="glyphicon glyphicon-user"></i> My stats</a>
      </li>
      <li>
        <a href="http://localhost/tickets"><i class="glyphicon glyphicon-th-list"></i> all tickets</a>
      </li>
      <li>
        <a href="http://localhost/articles"><i class="glyphicon glyphicon-tasks"></i> all articles</a>
      </li>
      <li>
        <a href="http://localhost/groups"><i class="glyphicon glyphicon-eye-open"></i> all teams</a>
      </li>
    </ul>

    <!-- time travel -->
    <button class="btn btn-primary btn-info pull-right hatch" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
      Time travel
    </button>

    <div class="collapse pull-right" id="collapseExample">
    <p><p/>
        <div class="btn-group btn-group-sm" role="group">
          <button id="setTimeWeek" type='button' class='btn btn-lg btn-default '>last week</button>
          <button id="setTimeMonth" type='button' class='btn btn-lg btn-default '>last month</button>
        </div>
        <p class="text-primary">Time travel the dashboard for this interval&hellip;</p>
        <p class="text-primary">Start Date:
          <input type="text" id="startDatePicker">
        </p>
        <p class="text-primary">End Date:
          <input type="text" id="endDatePicker">
        </p>
        <button id="timeTravelTrigger" type="button" class="btn btn-warning">GO!</button>

    </div>
    <!-- END time travel -->
  </div>

<section>
  <!-- Morris chart -->
  <div class="col-md-6 col-sm-12 col-xs-12 ">
    <div class="panel panel-default">
      <div class="panel-heading">
        Team's point score
      </div>


      <div class="panel-body">
        <div id="morris-bar-chart">
        </div>
      </div>
    </div>
  </div>
  <!-- END Morris chart -->
  </section>

  <!-- Morris chart -->
  <section>
    <div class="col-md-6 col-sm-12 col-xs-12 ">
      <div class="panel panel-default">
        <div class="panel-heading">
          Tickets per status
        </div>
        <div class="panel-body">
            <div id="donut-example"></div>
        </div>
        </div>
      </div>
    </div>
  </section>
  <!-- END Morris chart -->


  <!-- Modal for player info -->
  <div class="modal fade" id="playerInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div id="playerDetails" class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Player details</h4>
              </div>
              <div class="modal-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Priority</th>
                      <th>Tickets</th>
                      <th>Points</th>
                    </tr>
                  </thead>
                  <tbody id='playerlist'></tbody>
                </table>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>




  <footer class="col-md-12 col-sm-6 col-lg-12">
    Copyright by Celfocus. Gamification page. All rights reserved.
  </footer>
  <!-- js includes before closing body -->
  <script src="assets/js/jquery-2.1.4.min.js"></script>
  <script src="assets/bootstrap-3.3.4/js/bootstrap.min.js"></script>
  <!-- not needed if jquery 2 works fine <script src="assets/js/jquery-1.10.2.js"></script> -->

  <!-- JQuerry currently not being used but may come in handy in the future-->
  <script src="assets/jquery-ui-1.11.4/jquery-ui.js"></script>
  <!-- <script src="assets/videojs/dist/video-js/video.js"></script>-->
  <!-- <script src="assets/BigVideo/lib/bigvideo.js"></script>-->
  <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
  <script src="assets/js/morris/morris.js"></script>
  <script src="assets/js/moment-with-locales.js" charset="utf-8"></script>
  <!-- Extra js for Object Oriented implementation -->
  <script src="assets/js/custom/ajax.js" type="text/javascript"></script>
  <script src="assets/js/custom/group.js" type="text/javascript"></script>
  <script src="assets/js/custom/ticket.js" type="text/javascript"></script>
  <script src="assets/js/custom/events.js" type="text/javascript"></script>
  <script src="assets/js/custom/player.js" type="text/javascript" charset="utf-8"></script>
  <script src="assets/js/custom/main.js" type="text/javascript"></script>
  <script src="assets/js/outdatedbrowser.min.js"></script>
  <script>
    /**
     *   OutdatedBrowser function. Displays
     *   warning message is the user is in an outdated browser
     */
    $(document).ready(function() {
      outdatedBrowser({
        bgColor: '#f25648',
        color: '#ffffff',
        lowerThan: 'transform',
        languagePath: 'assets/en.html'
      })
    })
  </script>
</body>

</html>
