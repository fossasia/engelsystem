<?php

class GroupPrivileges_model_test extends PHPUnit_Framework_TestCase {

  private $group_id = null;
  private $group_id2 = null;
  private $priv_id = null;
  private $priv_id2 = null;

  public function create_Groups_Privileges() {
    $this->group_id = create_new_group('test', -100);
    $this->group_id2 = create_new_group('test1', -101);
    $this->priv_id = sql_query(INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (100, 'test1', 'testing Priveleg1'));
    $this->priv_id2 = sql_query(INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (101, 'test2', 'testing Privilege2'));
  }


  public function test_Groups_Privileges_create() {
		$count_grp = count(Groups_by_name());
		$this->assertNotFalse(create_new_group('test2', -102));
    $count_priv = sql_num_query("SELECT * FROM `Privileges`");
		$this->assertNotFalse(sql_query(INSERT INTO `Privileges` (`id`, `name`, `desc`) VALUES (102, 'test3', 'testing Priveleg3')));

		// There should be one more Groups entry now
    $this->assertEquals(count(Groups_by_name()), $count_grp + 1);

    // There should be one more Privileges entry now
    $this->assertEquals(count(sql_query("SELECT * FROM `Privileges`")), $count_priv + 1);
	}

	public function test_GroupPrivileges() {
		$this->create_Groups();
		$Groups = Groups_by_name($this->group_id);
    $GroupPrivileges_source = insert_GroupPrivilege($group_id['UID'], $priv_id['id']);
    $this->assertNotFalse($GroupPrivileges_source);
    $this->assertNotNull($GroupPrivileges_source);

	}

	public function teardown() {
		if ($this->group_id != null)
			delete_GroupPrivileges($this->group_id['UID']);
	}

}
?>