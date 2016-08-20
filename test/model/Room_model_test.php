<?php

class Room_model_test extends PHPUnit_Framework_TestCase {

  private $room_id = null;

  public function create_Room() {
    $this->room_id = Room_create('test', false, true, '');
  }

  public function test_Room_create() {
    $count = count(Room());
		$this->assertNotFalse(Room_create('test1', false, true, ''));

		// There should be one more Shift entry now
    $this->assertEquals(count(Room()), $count + 1);
  }

  public function test_Room() {
    $this->create_Room();
    $room = Room($this->room_id);
    $this->assertNotFalse($room);
    $this->assertTrue(count(Rooms()) > 0);
    $this->assertNotNull($room);
    $this->assertEquals($room['Name'], 'test');
    $this->assertEquals(count(Rooms()), 0);
    $this->assertNull(Room(- 1));
  }

  /**
   * @after
   */
  public function teardown() {
    if ($this->room_id != null)
      Room_delete($this->room_id);
  }

}
?>