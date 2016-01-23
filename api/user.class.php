<?php
class User {
	public $uid;
	public $username;
	private $password;
	private $email;
	private $level;

	public function init($username, $password, $email, $level = 1) {
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->level = $level;
	}
	public function checkVariables() {
		if(!preg_match('/^[a-zA-Z][a-zA-Z0-9]{4,29}$/', $this->username)) return false;
		if(!preg_match('/^[0-9a-zA-Z_-]+@[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+)+$/', $this->email)) return false;
		if(!preg_match('/^[0-9]$/', $this->level)) return false;
		return true;
	}
	public function getUidByUsername($username) {
		if(($sqlUser = @mysql_query('SELECT `uid` FROM `user` WHERE `username` = "'.$username.'";')) === false) return 0;
		return (int)@mysql_result($sqlUser, 0, 'uid');
	}
	public function getData() {
		if(($sqlUser = @mysql_query('SELECT 
				`uid`, `username`, `password`, `email`, `level`
			FROM `user`
			WHERE `uid` = "'.$this->uid.'";')) === false) return false;
		if(($response = @mysql_fetch_assoc($sqlUser)) === false) return false;
		$response['uid'] = (int)$response['uid'];
		$this->username = $response['username'];
		$this->password = $response['password'];
		$this->email = $response['email'];
		$this->level = $response['level'] = (int)$response['level'];
		$response['avatar'] = '//gravatar.duoshuo.com/avatar/'.md5($response['email']);
		return json_encode($response);
	}
	public function listData($username = "") {
		if(($sqlUser = @mysql_query('SELECT 
				`uid`, `username`, `password`, `email`, `level`
			FROM `user`
			WHERE `username` LIKE "%'.$username.'%";')) === false) return false;
		$response = [];
		while(($item = @mysql_fetch_assoc($sqlUser)) !== false) {
			$item['uid'] = (int)$item['uid'];
			$item['level'] = (int)$item['level'];
			unset($item['password']);
			$item['avatar'] = '//gravatar.duoshuo.com/avatar/'.md5($item['email']);
			array_push($response, $item);
		}
		// var_dump($response);
		return json_encode($response);
	}
	public function create() {
		if(($sqlUser = @mysql_query('INSERT INTO `user`
				(`username`, `password`, `email`, `level`)
			VALUES (
				"'.$this->username.'",
				"'.$this->password.'",
				"'.$this->email.'",
				"'.$this->level.'"
			);')) === false) return false;
		return (int)mysql_insert_id();
	}
	public function modify() {
		if(($sqlUser = @mysql_query('UPDATE `user`
			SET 
				`password` = "'.$this->password.'",
				`level` = "'.$this->level.'"
			WHERE `uid` = "'.$this->uid.'";')) === false) return false;
		return true;
	}
}
