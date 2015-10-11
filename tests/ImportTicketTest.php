<?php

class ImportUserTest extends TestCase
{

	/**
	 * Test user creation through OTRS
	 *
	 * @return void
	 */
	public function testUserCreation()
	{
		$thresholdID = \App\Ticket::orderBy('id', 'desc')->first()->id;
		var_dump($thresholdID);
		$client = new SoapClient(NULL,
			['location' => 'http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/GenericTicketConnector?wsdl',
				'uri' => "http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/GenericTicketConnector?wsdl"]);
		//making soap call to SessionCreate, this returns the session for future calls
		$sessionKey = $client->__soapCall("SessionCreate", array(
			new SoapParam("gameon", "UserLogin"),
			new SoapParam("Celfocus2015", "Password")
		));
		if ($sessionKey == null) {
			//session key is missing sending to signify error
			Log::warning("invalid response from the gamification webservices couldnt request session ID");
			return null;
		}
		//making soap call to get new tickets
		$client2 = new SoapClient(NULL,
			['location' => 'http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/Gamification?wsdl',
				'uri' => "http://193.236.121.122/otrs/nph-genericinterface.pl/Webservice/Gamification?wsdl"]);

		$receivedTicketsResponse = $client2->__soapCall("GamificationRanking", array(
			new SoapParam($sessionKey, "SessionID"),
			new SoapParam($thresholdID, "TicketTresholdID")
		));

		var_dump($receivedTicketsResponse);
	}

}
