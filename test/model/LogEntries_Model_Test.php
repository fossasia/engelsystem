<?php

class LogEntries_Model_Test extends PHPUnit_Framework_TestCase {

  public function create_LogEntry() {
    LogEntry_create('test', 'test');
  }

  public function test_LogEntry_create() {
    $count = count(LogEntries());
    $this->assertNotFalse(LogEntry_create('test', 'test_LogEntry_create'));

    // There should be one more log entry now
    $this->assertEquals(count(LogEntries()), $count + 1);
  }

  public function test_LogEntries_clear_all() {
    $this->create_LogEntry();
    $this->assertTrue(count(LogEntries()) > 0);
    $this->assertNotFalse(LogEntries_clear_all());
    $this->assertEquals(count(LogEntries()), 0);
  }

  /**
   * @after
   */
  public function teardown() {
    LogEntries_clear_all();
  }
}

?>
