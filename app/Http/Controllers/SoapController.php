<?php namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\Log;
use Psy\Exception\Exception;
use SoapClient;
use Illuminate\Http\Request;
use SoapHeader;
use SoapParam;


class SoapController extends Controller {

	public function index()
	{
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


        /***
		$client = new SoapClient('file:///C:/Users/nb21334/Desktop/webservices/GenericTicketConnector.wsdl',
			array('location' => "http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/GenericTicketConnector",
				'uri' => "http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/GenericTicketConnector",
				'enconding' => 'UTF-8',
				'trace' => 1));
        $client = new SoapClient("http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/GenericTicketConnector");

		//dump($client->__getFunctions());
        //dump($client->__getTypes());
        //$client->SessionCreate("SessionCreate", array("SessionCreate" => array("UserLogin" => "gameon","Password" => "Celfocus2015")));
        $client->__soapCall("SessionCreate", array("UserLogin" => "gameon","Password" => "Celfocus2015"),NULL, new SoapHeader());
		//dump($client->__getLastRequestHeaders());
        dump($client->__getLastRequest());
        return '';
        */
	}

}
