<?php

/**
 * Created by PhpStorm.
 * User: NB21334
 * Date: 08/10/2015
 * Time: 13:10
 */
class GetTicketWSDLtest extends PHPUnit_Framework_TestCase
{
	public function testBasicExample()
	{
		$client = new SoapClient(NULL,
			['location' => 'http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/GenericTicketConnector?wsdl',
				'uri'=>"http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/GenericTicketConnector?wsdl"]);
		//making soap call to SessionCreate, this returns the session for future calls
		$sessionKey = $client->__soapCall("SessionCreate", array(
			new SoapParam("gameon","UserLogin"),
			new SoapParam("Celfocus2015","Password")
		));
		if($sessionKey == null){
			//session key is missing sending to signify error
			echo ("invalid response from the gamification webservices couldnt request session ID");
		}
		$ticketGet = $client->__soapCall("TicketGet", array(
			new SoapParam($sessionKey,"SessionID"),
			new SoapParam("209388","TicketID")
		));
		var_dump($ticketGet);
				echo $ticketGet->Service.' '.$ticketGet->Owner;
	}
}
