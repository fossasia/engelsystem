<?php

class NewsComments_model_test extends PHPUnit_Framework_TestCase {

	private $news_id = null;
	private $news_id2 = null;
  private $newscomm_id = null;
	private $newscomm_id2 = null;

	public function create_News_and_comments() {
		$this->news_id = insert_news_val('Subject1', 'Message1', 1, 0);
		$this->news_id2 = insert_news_val('Subject2', 'Message2', 1, 1);
    $this->newscomm_id = insert_news($news_id['ID'], 'Comment1', 1);
    $this->newscomm_id2 = insert_news($news_id2['ID'], 'comment2', 1);
	}

	public function test_News_Comments_create() {
		$count_news = count(select_news());
		$this->assertNotFalse(insert_news_val('Subject3', 'Message3', 1, 1));
    $count_newscomm = count(select_newscomments_by_id($news_id['ID']));
		$this->assertNotFalse(insert_news($news_id['ID'], 'Comment3', 1));

		// There should be one more News entry now
    $this->assertEquals(count(select_news()), $count_news + 1);

    // There should be one more NewsComments entry now
    $this->assertEquals(count(select_newscomments_by_id($news_id['ID'])), $count_newscomm + 1);
	}

	public function test_NewsComments() {
		$this->create_News_and_comments();
		$NewsComments = select_newscomments_by_id($this->news_id['ID']);
 		$this->assertNotFalse($NewsComments);
    $this->assertNotNull($NewsComments);
		$this->assertTrue(count(select_newscomments_by_id($this->news_id['ID'])) > 0);
		$this->assertEquals($NewsComments['Text'], 'Comment1');
		$this->assertNull(select_newscomments_by_id(-1));
	}

}
?>