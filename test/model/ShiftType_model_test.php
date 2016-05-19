<?php

class ShiftTypes_model_test extends PHPUnit_Framework_TestCase  {

private $shift_id = null;

public function create_ShiftType(){
	$this->shift_id=ShiftType_create('test','1','test_description');
}

public function test_ShiftType(){
	$this->create_ShiftType();
	$shift_type=ShiftType($this->shift_id);
 	$this->assertNotFalse($shift_type);
    $this->assertNotNull($shift_type);
}
}
?>