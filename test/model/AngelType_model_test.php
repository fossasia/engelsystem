<?php

class AngelType_model_test extends PHPUnit_Framework_TestCase {

	private $angle_id = null;
	private $angle_id2 = null;

	public function create_AngelType()
	{
		$this->angle_id = AngelType_create('test', true, 'test', true);
		$this->angle_id2 = AngelType_create('test1', true, 'test', true);
	}

	public function test_AngelType_create() {
		$count = count(Angeltypes());
		$this->assertNotFalse(Angeltype_create('test2', true, 'test', true));

		// There should be one more AngelType entry now
    $this->assertEquals(count(AngelTypes()), $count + 1);
	}

	public function test_AngelType() {
		$this->create_AngelType();
		$Angeltype = AngelType($this->angle_id);
 		$this->assertNotFalse($Angeltype);
    $this->assertNotNull($Angeltype);
		$this->assertTrue(count(AngelTypes()) > 0);
		$this->assertEquals($Angeltype['name'], 'test');
		$this->assertEquals($Angeltype['name'], 'test1');
		$this->assertEquals(count(AngelTypes()), 0);
		$this->assertNull(Angeltype(- 1));
		$this->assertTrue(count(AngelTypes_ids()) > 0);
	}

	public function teardown() {
		if ($this->angel_id != null)
			AngelType_delete($this->angel_id);
	}

}
?>