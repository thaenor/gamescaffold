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
  <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
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


  <h1>Celfocus Gamification front end</h1>
<hr/>

  <div class="col-md-6 col-sm-6 col-lg-6 slideRight">
    <div class="panel panel-default">
      <div class="panel-heading">
        panel-heading
      </div>


      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active">
            <a data-toggle="tab" href="#ticketDisplayScreen">Tickets</a>
          </li>


          <li class="">
            <a data-toggle="tab" href="#rewards">Rewards</a>
          </li>


          <li class="">
            <a data-toggle="tab" href="#newsfeed">Newsfeed</a>
          </li>


          <li class="">
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
              <div class="col-lg-12 col-md-12 col-sm-12 bounce">
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
            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          </div>


          <div class="tab-pane fade" id="Gleaderboard">
            <h4>Leaderboard</h4>
            <!-- group leaderboard -->

            <div class="col-md-12 slideLeft">
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
                  <div class="table-responsive">
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
            <div id="playerLeaderboard" class="table-responsive"></div>
          </div>

        </div>
      </div>
    </div>
  </div>



  <!-- Morris chart -->
  <div class="col-md-6 col-sm-12 col-xs-12 slideUp">
    <div class="panel panel-default">
      <div class="panel-heading">
        Bar Chart
      </div>


      <div class="panel-body">
        <div id="morris-bar-chart">
        </div>
      </div>
    </div>
  </div>
  <!-- END Morris chart -->



  <div class="well col-md-6 col-sm-6 col-lg-6 slideRight">
      <!-- time travel -->
      <button class="btn btn-primary btn-info pull-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
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
            <input type="text" id="startDatepicker">
          </p>
          <p class="text-primary">End Date:
            <input type="text" id="endDatepicker">
          </p>
          <button id="timeTravelTrigger" type="button" class="btn btn-warning">GOooo!</button>

      </div>
      <!-- END time travel -->
  </div>

  <div class="col-md-12 col-lg-12 col-sm-12">
    <hr/>
    <div class="col-sm-6 col-md-3 text-center col-md-offset-3 col-lg-offset-3 col-sm-offset-3">
      <img src="assets/img.png"></img>
    </div>
  </div>

  


  <!-- red div for alerts
    <div id="alertDiv" class="pull-right alert alert-danger alert-dismissible fade in pulse " style="position: fixed; top: 30px; right: 5px;">
      <button  type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span id="dangerlabel" aria-hidden="true">&times;</span>
      </button>
    </div>
-->

  <footer class="col-md-12 col-sm-6 col-lg-12">
    Copyright by Francisco Santos. Gamification page. All rights reserved.
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
  <script src="assets/js/custom/group.js" type="text/javascript"></script>
  <script src="assets/js/custom/ticket.js" type="text/javascript"></script>
  <script src="assets/js/custom/events.js" type="text/javascript"></script>
  <script src="assets/js/custom/extras.js" type="text/javascript"></script>
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
