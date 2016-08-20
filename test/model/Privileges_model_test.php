<?php

class Privileges_model_test extends PHPUnit_Framework_TestCase {

  private $priv_id = null;
  private $priv_id2 = null;

  public function create_Privilege()  {
    $this->priv_id = sql_query(INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (100, 'test1', 'testing Priveleg1'));
    $this->priv_id2 = sql_query(INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (101, 'test2', 'testing Privilege2'));
  }


  public function test_Privileges_create() {
		$count = sql_num_query("SELECT * FROM `Privileges`");
		$this->assertNotFalse(sql_query(INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (102, 'test3', 'testing Priveleg3')));

		// There should be one more Privileges entry now
    $this->assertEquals(count(sql_query("SELECT * FROM `Privileges`")), $count + 1);
	}

	public function test_Privileges() {
		$this->create_Privilege();
    $Privilege = Privileges_by_id($this->$priv_id['id']);
    $this->assertNotFalse($Privilege);
    $this->assertNotNull($Privilege);
    $this->assertEquals($priv_id['name'], 'test1');
    $this->assertEquals($priv_id2['name'], 'test2');
    $this->assertNull(Privileges_by_id(- 1));
	}

}
?>