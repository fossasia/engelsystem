<?php

class Shifts_Model_test extends PHPUnit_Framework_TestCase {

	private $shift_id = null;
	private $shift_id1 = null;

	public function create_shift() {
		$shift_id['RID'] = 0;
		$shift_id['start'] = $shift_id['end'] = DateTime::createFromFormat("Y-m-d H:i", date("Y-m-d") . " 00:00")->getTimestamp();
		$shift_id['title'] = "";
		$shift_id['shifttype_id'] = null;
		$shift_id['URL'] = null;
		$shift_id['PSID'] = null;
		$shift_id['created_by_user_id'] = null;
		$shift_id['created_at_timestamp'] = time();
		$this->shift_id1 = Shift_create($shift_id);
	}

	public function test_Shift_create() {
		$count = count(Shifts());
		$this->assertNotFalse(Shift_create($shift_id));

		// There should be one more Shift entry now
		$this->assertEquals(count(Shifts()), $count + 1);

	}

	public function test_Shifts() {
		$this->create_shift();
		$Shift = Shifts($this->shift_id1);
		$this->assertNotFalse($Shift);
		$this->assertTrue(count(Shifts()) > 0);
		$this->assertNotNull($Shift);
		$this->assertEquals($Shift['title'], '');
		$this->assertEquals(count(Shifts()), 0);
		$this->assertNull(Shifts(- 1));
	}

	public function teardown() {
		if ($this->shift_id != null)
			Shift_delete($this->shift_id);
	}
}
?>