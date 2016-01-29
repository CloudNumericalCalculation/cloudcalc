<?php
class Article{
	public $aid;
	private $title;
	private $content;
	private $visibility;
	private $notice;

	public function init($title, $content, $visibility=0, $notice=0) {
		$this->title = $title;
		$this->content = $content;
		$this->visibility = $visibility;
		$this->notice = $notice;
	}
	public function checkVariables() {
		if(!preg_match('/^[0-1]$/', $this->visibility)) return false;
		if(!preg_match('/^[0-1]$/', $this->notice)) return false;
		return true;
	}
	public function getData(){
		if(($sqlArticle = @mysql_query(
			'SELECT `aid`, `title`, `content`, `visibility`, `notice`
			FROM `article`
			WHERE `aid` = "'.$this->aid.'";')) === false) return false;
		if(($response = @mysql_fetch_assoc($sqlArticle)) === false) return false;
		$response['aid'] = (int)$response['aid'];
		$this->title = $response['title'] = urldecode($response['title']);
		$this->content = $response['content'] = urldecode($response['content']);
		$this->visibility = $response['visibility'] = (bool)$response['visibility'];
		$this->notice = $response['notice'] = (bool)$response['notice'];
		return json_encode($response);
	}
	public function listData($notice=false){
		$conditionStr = '';
		if($notice == true) $conditionStr = 'WHERE `notice` = "1"';
		if(($sqlArticle = @mysql_query(
			'SELECT `aid`, `title`, `content`, `visibility`, `notice`
			FROM `article` '.$conditionStr.';')) === false) return false;
		$response = [];
		while(($item = @mysql_fetch_assoc($sqlArticle)) !== false) {
			$item['aid'] = (int)$item['aid'];
			$item['title'] = urldecode($item['title']);
			$item['content'] = urldecode($item['content']);
			$item['visibility'] = (bool)$item['visibility'];
			$item['notice'] = (bool)$item['notice'];
			if(!checkAuthority(9) && !$item['visibility']) continue;
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
