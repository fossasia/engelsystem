<?php

class Questions_model_test extends PHPUnit_Framework_TestCase {

	private $ques_id = null;
	private $ques_id2 = null;

	public function create_Questions() {
		$this->ques_id = insert_new_question(1, 'Question 1');
		$this->ques_id2 = insert_new_question(1, 'Question 2');
	}

	public function test_Questions_create() {
		$count = count(Questions());
		$this->assertNotFalse(insert_new_question(1, 'Question 3'));

		// There should be one more Questions entry now
    $this->assertEquals(count(Questions()), $count + 1);
	}

	public function test_Questions() {
		$this->create_Questions();
		$Ques = select_ques_by_id($this->ques_id['QID']);
 		$this->assertNotFalse($Ques);
    $this->assertNotNull($Ques);
		$this->assertTrue(count(Questions()) > 0);
		$this->assertEquals($ques_id['Question'], 'Question 1');
		$this->assertEquals($ques_id2['Question'], 'Question 2');
		$this->assertEquals(count(Questions()), 0);
		$this->assertNull(select_ques_by_id(-1));
	}

	public function teardown() {
		if ($this->ques_id != null)
			delete_ques_by_id($this->ques_id['QID']);
	}

}
?>