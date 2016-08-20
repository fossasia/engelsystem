<?php

class NeededAngelType_Model_test extends PHPUnit_Framework_TestCase {

	private $shift_id1 = null;
	private $shift_id2 = null;

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
		$this->shift_id2 = Shift_create($shift_id);
	}

	public function test_NeededAngeltype() {
		$this->create_shift();
		$NeededAngeltype = NeededAngelTypes_by_shift($this->shift_id1);
		$this->assertNotFalse($NeededAngeltype);
		$this->assertNotNull($NeededAngeltype);
	}
}
?>