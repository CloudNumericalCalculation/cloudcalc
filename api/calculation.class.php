<?php
class Calculation{
	public $cid;
	private $pid;
	private $uid;
	private $priority;
	private $public;
	private $password;
	private $status;
	private $input;
	private $result;

	public function init($pid, $priority, $public, $password,$status,$input) {
		$this->pid = $pid;
		$this->priority = $priority;
		$this->public = $public;
		$this->password = $password;
		$this->status = $status;
		$this->input = $input;
	}
	public function checkVariables() {
		if(!preg_match('/^[0-9]$/', $this->priority)) return false;
		if(!preg_match('/^[0-5]$/', $this->level)) return false;
	}
	public function getData(){
		if(($sqlCalculation = @mysql_query(
			'SELECT `pid`,`uid`, `priority`, `public`, `password`, `status`,`input`,`result`
			FROM `calculation`
			WHERE `cid` = "'.$this->cid.'";')) === false) return false;
		if(($response = @mysql_fetch_assoc($sqlCalculation)) === false) return false;
		$this->pid = $response['pid'];
		$this->uid = $response['uid'];
		$this->priority = $response['priority'];
		$this->public = $response['public'];
		$this->password = $response['password'];
		$this->status = $response['status'];
		$this->input = urldecode($response['input']);
		$this->result = urldecode($response['result']);
		return json_encode($response);
	}
	public function listData(uid=""){
		if(($sqlCalculation = @mysql_query(
			'SELECT `uid`, `priority`, `public`, `password`, `status`,`input`,`result`
			FROM `calculation`
			WHERE `uid` = "'.$this->uid.'";')) === false) return false;
		$response = [];
		while(($item = @mysql_fetch_assoc($sqlCalculation)) !== false) {
			$item['uid'] = (int)$item['uid'];
			array_push($response, $item);
		}
		return json_encode($response);
	}
	public function create(){
		if(($sqlCalculation = @mysql_query(
			'INSERT INTO `calculation`
				(`priority`, `public`, `password`, `status`,`input`,`result`)
			VALUES (
				"'.$this->priority.'",
				"'.$this->public.'",
				"'.$this->password.'",
				"'.$this->status.'",
				"urlencode('.$this->input.')",
				"urlencode('.$this->result.')"
			);')) === false) return false;
		return (int)mysql_insert_id();
	}
	public function modify(){
		if(($sqlCalculation = @mysql_query(
			'UPDATE `calculation`
			SET 
				`priority` = "'.$this->priority.'",
				`public` = "'.$this->public.'",
				`password` = "'.$this->password.'",
				`status` = "'.$this->status.'",
				`input` = "urlencode('.$this->input.')",
				`result` = "urlencode('.$this->result.')"
			WHERE `cid` = "'.$this->cid.'";')) === false) return false;
		return true;
	}
}