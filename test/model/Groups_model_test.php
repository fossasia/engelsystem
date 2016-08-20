<?php

class Groups_model_test extends PHPUnit_Framework_TestCase {

	private $group_id = null;
	private $group_id2 = null;

	public function create_Group() {
		$this->group_id = create_new_group('test', -100);
		$this->group_id2 = create_new_group('test1', -101);
	}

	public function test_Group_create() {
		$count = count(Groups_by_name());
		$this->assertNotFalse(create_new_group('test2', -102));

		// There should be one more Groups entry now
    $this->assertEquals(count(Groups_by_name()), $count + 1);
	}

	public function test_Group() {
		$this->create_Group();
		$Group_name = Groups_by_name($this->group_id);
 		$this->assertNotFalse($Group_name);
    $this->assertNotNull($Group_name);
		$this->assertTrue(count(Groups_by_name()) > 0);
		$this->assertEquals($Group_name['Name'], 'test');
		$this->assertEquals($Group_name['Name'], 'test1');
		$this->assertEquals(count(Groups_by_name()), 0);
		$this->assertNull(Groups_by_id(1));
		$this->assertTrue(count(selects_groups_by_id()) > 0);
	}

	public function teardown() {
		if ($this->group_id != null)
			delete_group($this->group_id['UID']);
	}

}
?>