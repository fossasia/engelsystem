<?php

class News_model_test extends PHPUnit_Framework_TestCase {

	private $news_id = null;
	private $news_id2 = null;

	public function create_News()	{
		$this->news_id = insert_news_val('Subject1', 'Message1', 1, 0);
		$this->news_id2 = insert_news_val('Subject2', 'Message2', 1, 1);
	}

	public function test_News_create() {
		$count = count(select_news());
		$this->assertNotFalse(insert_news_val('Subject3', 'Message3', 1, 1));

		// There should be one more News entry now
    $this->assertEquals(count(select_news()), $count + 1);
	}

	public function test_News() {
		$this->create_News();
		$News = select_news_by_id($this->news_id['ID']);
 		$this->assertNotFalse($News);
    $this->assertNotNull($News);
		$this->assertTrue(count(select_news()) > 0);
		$this->assertEquals($news_id['Betreff'], 'Subject1');
		$this->assertEquals($news_id2['Betreff'], 'Subject2');
		$this->assertEquals(count(select_news()), 0);
		$this->assertNull(select_news_by_id(-1));
	}

	public function teardown() {
		if ($this->$news_id != null)
			delete_by_id($this->news_id['ID']);
	}

}
?>