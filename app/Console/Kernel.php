<?php namespace App\Console;

use App\Ticket;
use App\Group;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SoapClient;
use SoapParam;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		/**
		 * Webservice implementation. Uses the webservice to fetch new tickets.
		 * Any errors are logged to a file in /storage/logs
		 * The webservice returns either a single ticket or an array with two or more
		 * This function handles that possibility and treats it accordingly.
		 * There are no webservices to update tickets or insert missing groups or users yet.
		 * If you want to force the webservice to run visit the url secretRoute/soap
		 *
		 * This function is executed every hour
		 */
		$schedule->call(function () {
            try{
	            //declaring SOAP client
	            $client = new SoapClient(NULL,
		            ['location' => 'http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/GenericTicketConnector?wsdl',
			            'uri'=>"http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/GenericTicketConnector?wsdl"]);

	            //making soap call to SessionCreate, this returns the session for future calls
	            $sessionKey = $client->__soapCall("SessionCreate", array(
		            new SoapParam("gameon","UserLogin"),
		            new SoapParam("Celfocus2015","Password")
	            ));

	            if($sessionKey == null){
		            return 'session key is missing';
	            }

	            //making soap call to get new tickets
	            $client2 = new SoapClient(NULL,
		            ['location' => 'http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/Gamification?wsdl',
			            'uri'=>"http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/Gamification?wsdl"]);

	            $lastTicketId = Ticket::take(1)->orderBy('id','desc')->first()->id;
	            $receivedTicketsResponse = $client2->__soapCall("GamificationRanking", array(
		            new SoapParam($sessionKey, "SessionID"),
		            new SoapParam($lastTicketId,"TicketTresholdID")
	            ));

	            $count = 0;
	            if( is_array($receivedTicketsResponse) ){
		            foreach($receivedTicketsResponse['ticket'] as $element){
			            Ticket::insertTicket($element);
			            $count++;
		            }
	            } else {
		            Ticket::insertTicket($receivedTicketsResponse);
		            $count ++;
	            }
	            echo 'Webservice data transfer complete, total data received '.$count;
	            Storage::disk('local')->put('lastsynctime.txt', Carbon::now());
            } catch(exception $e){
                Log::warning('Something could be going wrong with the webservice communication - '.$e);
            }
        })->hourly();

		/**
		 * Every month points are reset in the permanent hall of fame (this corresponds to the default group table
		 * on the first page)
		 */
		$schedule->call(function () {
			Ticket::resetPoints();
		})->monthly();

		/**
		 * Watches for any ticket with ReoPened state and sets penalties for it.
		 */
		//$schedule->call(function() {
		//	Ticket::setTicketPenalties();
		//})->everyTenMinutes();
	}

}
