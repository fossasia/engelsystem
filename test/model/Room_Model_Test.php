<?php

class Room_Model_Test extends PHPUnit_Framework_TestCase {

  private $room_id = null;

  public function create_Room() {
    $this->room_id = Room_create('test', false, true, '');
  }

  public function test_Room() {
    $this->create_Room();

    $room = Room($this->room_id);

    $this->assertNotFalse($room);
    $this->assertNotNull($room);
    $this->assertEquals($room['Name'], 'test');

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
