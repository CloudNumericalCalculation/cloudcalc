<?php
class Article{
	public $aid;
	private $title;
	private $content;
	private $visibility;
	private $notice;

	public function init($title, $content, $visibility, $notice) {
		$this->title = $title;
		$this->content = $content;
		$this->visibility = $visibility;
		$this->notice = $notice;
	}
	public function checkVariables() {
		if(!preg_match('/^[0-1]$/', $this->visibility)) return false;
		if(!preg_match('/^[0-1]$/', $this->notice)) return false;
	}
	public function getData(){
		if(($sqlArticle = @mysql_query(
			'SELECT `aid`, `title`, `content`, `visibility`, `notice`
			FROM `article`
			WHERE `aid` = "'.$this->aid.'";')) === false) return false;
		if(($response = @mysql_fetch_assoc($sqlArticle)) === false) return false;
		$this->title = urldecode($response['title']);
		$this->content = urldecode($response['content']);
		$this->visibility = (int)$response['visibility'];
		$this->notice = (int)$response['notice'];
		return json_encode($response);
	}
	public function listData(){
		if(($sqlArticle = @mysql_query(
			'SELECT `aid`, `title`, `content`, `visibility`, `notice`
			FROM `article`;')) === false) return false;
		$response = [];
		while(($item = @mysql_fetch_assoc($sqlArticle)) !== false) {
			$item['aid'] = (int)$item['aid'];
			$item['title'] = urldecode($item['title']);
			$item['content'] = urldecode($item['content']);
			$item['visibility'] = (int)$item['visibility'];
			$item['notice'] = (int)$item['notice'];
			array_push($response, $item);
		}
		return json_encode($response);
	}
	public function create(){
		if(($sqlArticle = @mysql_query(
			'INSERT INTO `article`
				(`title`, `content`, `visibility`, `notice`)
			VALUES (
				"'.urlencode($this->title).'",
				"'.urlencode($this->content).'",
				"'.$this->visibility.'",
				"'.$this->notice.'"
			);')) === false) return false;
		return (int)mysql_insert_id();
	}
	public function modify(){
		if(($sqlArticle = @mysql_query(
			'UPDATE `article`
			SET 
				`title` = "'.urlencode($this->title).'",
				`content` = "'.urlencode($this->content).'",
				`visibility` = "'.$this->visibility.'",
				`notice` = "'.$this->notice.'"
			WHERE `aid` = "'.$this->aid.'";')) === false) return false;
		return true;
	}
}
