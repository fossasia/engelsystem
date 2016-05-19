<?php
class Message_model_test extends PHPUnit_Framework_TestCase {

private $message_id = null ;

public function test_Message_send(){
	$this->message_id = Message_ids();
	if(count($this->message_id)==0){
		$this->assertEquals(count($this->message_id),0);
	}	
	else{
	$this->assertTrue(Message_send($this->message_id[0],'test message'));	
	}
} 
}

?>