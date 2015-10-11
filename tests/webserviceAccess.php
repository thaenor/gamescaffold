<?php
use Illuminate\Support\Facades\Storage;

/**
 * Created by PhpStorm.
 * User: NB21334
 * Date: 01/10/2015
 * Time: 14:26
 */
class webserviceAccess extends PHPUnit_Framework_TestCase
{
	public function testBasicExample()
	{
		$webservice = fopen('D:\vagrant\celfocusVM\code\storage\app\webservice_mock.xml', "r");
		$buffer = '';
		if ($webservice) {
			while (!feof($webservice)) {
				$buffer .= fgetss($webservice, 5000);
			}

			fclose($webservice);
		}
		var_dump($buffer);
	}
}
