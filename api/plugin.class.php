<?php
class Plugin{
	public $pid;
	private $uid;
	private $folder;
	private $cover;
	private $name;
	private $author;
	private $git;

	public function init($uid, $folder, $cover, $name, $author, $git) {
		$this->uid = $uid;
		$this->folder = $folder;
		$this->cover = $cover;
		$this->name = $name;
		$this->author = $author;
		$this->git = $git;
	}
	public function checkVariables() {
		if(!preg_match('/^[0-9]+$/', $this->uid)) return false;
		return true;
	}
	public function getData(){
		if(($sqlPlugin = @mysql_query(
			'SELECT `pid`, `uid`, `folder`, `cover`, `name`, `author`, `git`
			FROM `plugin`
			WHERE `pid` = "'.$this->pid.'";')) === false) return false;
		if(($response = @mysql_fetch_assoc($sqlPlugin)) === false) return false;
		$response['pid'] = (int)$response['pid'];
		$this->uid = $response['uid']= (int)$response['uid'];
		$this->folder = $response['folder'] = urldecode($response['folder']);
		$this->cover = $response['cover'] = urldecode($response['cover']);
		$this->name = $response['name'] = urldecode($response['name']);
		$this->author = $response['author'] = urldecode($response['author']);
		$this->git = $response['git'] = urldecode($response['git']);
		return json_encode($response);
	}
	public function listData($uid=""){
		if(($sqlPlugin = @mysql_query(
			'SELECT `pid`, `uid`, `folder`, `cover`, `name`, `author`, `git`
			FROM `plugin`
			WHERE `uid` LIKE "%'.$uid.'%";')) === false) return false;
		$response = [];
		while(($item = @mysql_fetch_assoc($sqlPlugin)) !== false) {
			$item['pid'] = (int)$item['pid'];
			$item['uid'] = (int)$item['uid'];
			$item['folder'] = urldecode($item['folder']);
			$item['cover'] = urldecode($item['cover']);
			$item['name'] = urldecode($item['name']);
			$item['author'] = urldecode($item['author']);
			$item['git'] = urldecode($item['git']);
			array_push($response, $item);
		}
		return json_encode($response);
	}
	public function create(){
		if(($sqlPlugin = @mysql_query(
			'INSERT INTO `plugin`
				(`uid`, `folder`, `cover`, `name`, `author`, `git`)
			VALUES (
				"'.$this->uid.'",
				"'.urlencode($this->folder).'",
				"'.urlencode($this->cover).'",
				"'.urlencode($this->name).'",
				"'.urlencode($this->author).'",
				"'.urlencode($this->git).'"
			);')) === false) return false;
		return (int)mysql_insert_id();
	}
	public function modify(){
		if(($sqlPlugin = @mysql_query(
			'UPDATE `plugin`
			SET 
				`cover` = "'.urlencode($this->cover).'",
				`name` = "'.urlencode($this->name).'",
				`author` = "'.urlencode($this->author).'",
				`git` = "'.urlencode($this->git).'"
			WHERE `pid` = "'.$this->pid.'";')) === false) return false;
		return true;
	}
}
