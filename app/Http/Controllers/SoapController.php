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
	}

}
