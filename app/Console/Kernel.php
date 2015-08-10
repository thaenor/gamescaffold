<?php namespace App\Console;

use App\Ticket;
use App\Group;
use App\User;
use Illuminate\Support\Facades\Log;
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

                //iterating each of the results and inserting them into the database
                foreach($receivedTicketsResponse['ticket'] as $element){
                    $ticket = Ticket::find($element->id);
                    if(!$ticket){
                        $ticket = new Ticket();
                    }
                    $ticket->id = $element->id;
                    $ticket->title = $element->title;
                    $ticket->type = $element->type_of_ticket;
                    $ticket->priority = $element->priority_id;
                    $ticket->state = $element->ticket_state;
                    $ticket->sla = $element->sla_name;
                    $ticket->sla_time = $element->solution_time;
                    $ticket->percentage = $element->percentage;
                    $ticket->created_at = $element->cretime;
                    $ticket->updated_at = $element->chgtime;
                    $ticket->user_id = $element->user_id;
                    $ticket->external_id = $element->remedy_id;
                    //tries to locate the user. If it does not exist, the data is imported
                    $user = User::find($element->user_id);
                    if(!$user){
                        echo 'warning unknown user';
                    }
                    $ticket->assignedGroup_id = $element->group_id;
                    //tries to locate the group. If it does not exist, the data is imported
                    $group = Group::find($element->group_id);
                    if(!$group){
                        echo 'warning unknown group';
                    }
                    //optional: importRelationUserGroup($element->user_id, $element->group_id);
                    $ticket->updateTicketPoints($ticket);
                    $ticket->save();
                }
                Log::info('Sucessfully retrieved new tickets.');
            } catch(exception $e){
                Log::warning('Something could be going wrong with the webservice communication - '.$e);
            }
        })->hourly();
		/*$schedule->command('inspire')
				 ->hourly();*/
	}

}
