<?php

class UserGroups_model_test extends PHPUnit_Framework_TestCase {

	private $group_id = null;
	private $user_id = null;
	private $userGroup_id = null;

	public function create_Users_Groups() {
		$this->user_id = User_insert(1234, 'testing1', 'lastname1', 'name1', 20, '', '-', '', 'testing1@testing.com', 1, '', 'XL', '$6$rounds=5000$hjXbIhoRTH3vKiRa$Wl2P2iI5T9iRR.HHu/YFHswBW0WVn0yxCfCiX0Keco9OdIoDK6bIAADswP6KvMCJSwTGdV8PgA8g8Xfw5l8BD1', NULL, 1, 1, 0, 1, 2, 'de_DE.UTF-8', 'L', 1439759300, '0000-00-00 00:00:00', '', '', '', '038850abdd1feb264406be3ffa746235', 3, 1439490478, 1436964455, 1440161255);
		$this->group_id = create_new_group('testgroup', -1000);
		$this->userGroup_id = insert_UserGroups_id(1234, -1000);
	}

	public function test_UserGroups_create() {
		$count_Users = count(Users());
		$this->assertNotFalse(User_insert(1234, 'testing3', 'lastname3', 'name3', 20, '', '-', '', 'testing3@testing.com', 1, '', 'XL', '$6$rounds=5000$hjXbIhoRTH3vKiRa$Wl2P2iI5T9iRR.HHu/YFHswBW0WVn0yxCfCiX0Keco9OdIoDK6bIAADswP6KvMCJSwTGdV8PgA8g8Xfw5l8BD1', NULL, 1, 1, 0, 1, 2, 'de_DE.UTF-8', 'L', 1439759300, '0000-00-00 00:00:00', '', '', '', '038850abdd1feb264406be3ffa746235', 3, 1439490478, 1436964455, 1440161255));
		$count_Groups = count();
		$this->assertNotFalse(create_new_group('testgroup', -1000));

		// There should be one more Users entry now
    $this->assertEquals(count(Users()), $count_Users + 1);

		// There should be one more Groups entry now
    $this->assertEquals(count(Groups_by_name()), $count_Groups + 1);
	}

	public function test_UsersGroups() {
		$this->create_Users_Groups();
		$user_Groups = UserGroups_by_id($this->userGroup_id['group_id']);
 		$this->assertNotFalse($user_Groups);
    $this->assertNotNull($user_Groups);
		$this->assertNull(UserGroups_by_id(1));
	}

	public function teardown() {
		if ($this->userGroup_id != null)
			delete_UserGroups_id($this->userGroup_id['UID']);
	}

}
?>