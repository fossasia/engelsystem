<?php

class Users_model_test extends PHPUnit_Framework_TestCase {

	private $user_id = null;
	private $user_id2 = null;

	public function create_Users() {
		$this->user_id = User_insert(1234, 'testing1', 'lastname1', 'name1', 20, '', '-', '', 'testing1@testing.com', 1, '', 'XL', '$6$rounds=5000$hjXbIhoRTH3vKiRa$Wl2P2iI5T9iRR.HHu/YFHswBW0WVn0yxCfCiX0Keco9OdIoDK6bIAADswP6KvMCJSwTGdV8PgA8g8Xfw5l8BD1', NULL, 1, 1, 0, 1, 2, 'de_DE.UTF-8', 'L', 1439759300, '0000-00-00 00:00:00', '', '', '', '038850abdd1feb264406be3ffa746235', 3, 1439490478, 1436964455, 1440161255);
		$this->user_id2 = User_insert(12345, 'testing2', 'lastname2', 'name2', 40, '', '-', '', 'testing2@testing.com', 1, '', 'XL', '$6$rounds=5000$hjXbIhoRTH3vKiRa$Wl2P2iI5T9iRR.HHu/YFHswBW0WVn0yxCfCiX0Keco9OdIoDK6bIAADswP6KvMCJSwTGdV8PgA8g8Xfw5l8BD1', NULL, 1, 1, 0, 1, 2, 'de_DE.UTF-8', 'L', 1439759300, '0000-00-00 00:00:00', '', '', '', '038850abdd1feb264406be3ffa746235', 3, 1439490478, 1436964455, 1440161255);
	}

	public function test_Users_create() {
		$count = count(Users());
		$this->assertNotFalse(User_insert(1234, 'testing3', 'lastname3', 'name3', 20, '', '-', '', 'testing3@testing.com', 1, '', 'XL', '$6$rounds=5000$hjXbIhoRTH3vKiRa$Wl2P2iI5T9iRR.HHu/YFHswBW0WVn0yxCfCiX0Keco9OdIoDK6bIAADswP6KvMCJSwTGdV8PgA8g8Xfw5l8BD1', NULL, 1, 1, 0, 1, 2, 'de_DE.UTF-8', 'L', 1439759300, '0000-00-00 00:00:00', '', '', '', '038850abdd1feb264406be3ffa746235', 3, 1439490478, 1436964455, 1440161255));

		// There should be one more Users entry now
    $this->assertEquals(count(Users()), $count + 1);
	}

	public function test_Users() {
		$this->create_Users();
		$user_testing = User($this->user_id['UID']);
 		$this->assertNotFalse($user_testing);
    $this->assertNotNull($user_testing);
		$this->assertTrue(count(Users()) > 0);
		$this->assertEquals($user_id['Nick'], 'testing1');
		$this->assertEquals($user_id['Nick'], 'testing2');
		$this->assertEquals(count(Users()), 0);
		$this->assertNull(Users(-1));
	}

	public function teardown() {
		if ($this->user_id != null)
			User_delete($this->user_id['UID']);
	}

}
?>