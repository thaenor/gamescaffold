<?php

class ImportUserTest extends TestCase {

	/**
	 * Test user creation through OTRS
	 *
	 * @return void
	 */
	public function testUserCreation()
	{
        require('otrsDAL.php');
        $dal = connect();
        $user = importUser(1);
        closeDB($dal);

        $this->assertClassHasAttribute($user, 'stdClass');
	}

}
