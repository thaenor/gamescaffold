<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>Celfocus Gamification</title>
    
    <!-- includes at head-->
    <link href="assets/css/animations.css" rel="stylesheet"/>
    <!-- Bootstrap jquery Styles-->
    <link href="assets/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet"/>
    <link href="assets/jquery-ui-1.11.4/jquery-ui.theme.css" rel="stylesheet"/>
    <link href="assets/bootstrap-3.3.4/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Big Video -->
    <!-- <link href="assets/BigVideo/css/bigvideo.css"/> -->
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- outdated browser pluggin-->
    <link rel="stylesheet" href="assets/css/outdatedbrowser.min.css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="outdated"></div>
    <h1>Celfocus Gamification front end</h1>
        
        <div class="col-md-12 col-sm-6 col-lg-12 slideRight">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            panel-heading
                        </div>
                        <div class="panel-body">
                            <ul  class="nav nav-tabs">
                                <li class="active"><a href="#home" data-toggle="tab">Tickets</a>
                                </li>
                                <li class=""><a href="#profile" data-toggle="tab">Bounty list</a>
                                </li>
                                <li class=""><a href="#messages" data-toggle="tab">Some chart(ToDo)</a>
                                </li>
                                <li class=""><a href="#settings" data-toggle="tab">Leaderboard</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade active in col-md-6 col-sm-3 col-lg-6" id="home">
                                    <h4>Tickets</h4>
                                        <nav class="col-md-12 col-sm-12 col-lg-12">
                                            <ul class="pager">
                                                <li class="previous"><a href="#"><span aria-hidden="true">&larr;</span> Previous</a></li>
                                                <li class="next"><a href="#">Next <span aria-hidden="true">&rarr;</span></a></li>
                                            </ul>
                                        </nav>
                                        
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 bounce">
                                                <ul id="ticketList" class="list-group pullUp">
                                                    <!-- LIST WITH TICKETS -->
                                                </ul>
                                            </div>
                                            <!-- search bar -->
                                                <div class="col-md-6 col-sm-12 col-lg-6">
                                                    <div class="input-group">
                                                        <div class="input-group input-group">
                                                          <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-search"></i></span>
                                                          <input id="ticketSearchField" type="text" class="form-control" placeholder="Search for..." aria-describedby="sizing-addon1">
                                                        </div>
                                                    </div><!-- /input-group -->
                                                </div><!-- /.col-lg-6 -->
                                        </div><!-- /.row -->
                                        <!-- END search bar -->
                                </div>
                                <div class="tab-pane fade" id="profile">
                                    <h4>Profile Tab</h4>
                                    <!-- REWARDS -->
                                    <p class="bigEntrance">this will show a tab with rewards available</p>
                                </div>
                                <div class="tab-pane fade" id="messages">
                                    <h4>Some chart(ToDo)</h4>
                                    
                                    <div class="col-md-6 col-lg-12 col-xs-12 well bigEntrance">
                                    
                                    <a class="twitter-timeline" href="https://twitter.com/Celfocus" data-widget-id="598535731947122688">Tweets by @Celfocus</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                                    </div>
                                    
                                    
                                    <p class="bounce">this will show a tab with graphs</p>
                                </div>
                                <div class="tab-pane fade" id="settings">
                                    <h4>Leaderboard</h4>
                                    
                                    
                                    <!-- group leaderboard -->        
                                            <div class="col-md-12 slideLeft">
                                                         <!--    Context Classes  -->
                                                        <div class="panel panel-default">
                                                           
                                                            <div id="leaderboard" class="panel-heading">
                                                                Group leaderboard
                                                            </div>

                                                            <nav>
                                                                <ul class="pager">
                                                                    <li class="previous"><a href="#"><span aria-hidden="true">&larr;</span> Previous</a></li>
                                                                    <li class="next"><a href="#">Next <span aria-hidden="true">&rarr;</span></a></li>
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
                                                                        <tbody id='grouplist'>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--  end  Context Classes  -->
                                                    </div>
                                    <!-- END group leaderboard -->  
                                    
                                    
                                    
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
                        <div id="morris-bar-chart"></div>
                    </div>
                </div>            
        </div>
<!-- END Morris chart -->


<div class="ui-widget col-md-12 col-md-offset-5 gap">
    DevDiv...
</div>


<footer class="col-md-12 col-sm-6 col-lg-12">Copyright by Francisco Santos. Gamification page. All rights reserved.</footer>

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
    <!-- Extra js for Object Oriented implementation 
    <script src="assets/js/custom/groupObj.js" type="text/javascript"></script>
    <script src="assets/js/custom/playerObj.js" type="text/javascript"></script>
    <script src="assets/js/custom/ticketObj.js" type="text/javascript"></script> -->
    <script src="assets/js/custom/main.js" type="text/javascript"></script>
    <script src="assets/js/outdatedbrowser.min.js"></script>
    <script>
    /**
    *   OutdatedBrowser function. Displays 
    *   warning message is the user is in an outdated browser
    */
        $( document ).ready(function() {
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