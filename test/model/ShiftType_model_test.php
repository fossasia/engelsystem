<?php

class ShiftTypes_Model_test extends PHPUnit_Framework_TestCase  {

	private $shift_id = null;

	public function create_ShiftType(){
		$this->shift_id = ShiftType_create('test', '1', 'test_description');
	}

	public function test_ShiftType_create() {
		$count = count(ShiftTypes());
		$this->assertNotFalse(create_ShiftType($shift_id));

		// There should be one more ShiftTypes now
		$this->assertEquals(count(ShiftTypes()), $count + 1);
	}

	public function test_ShiftType(){
		$this->create_ShiftType();
		$shift_type = ShiftType($this->shift_id);
 		$this->assertNotFalse($shift_type);
		$this->assertTrue(count(ShiftTypes()) > 0);
		$this->assertNotNull($shift_type);
		$this->assertEquals($shift_type['name'], 'test');
		$this->assertEquals(count(ShiftTypes()), 0);
		$this->assertNull(ShiftTypes(-1));
	}

	public function teardown() {
		if ($this->shift_id != null)
			ShiftType_delete($this->shift_id);
	}

}

?>