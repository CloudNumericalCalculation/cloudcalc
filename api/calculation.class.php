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

	public function init($pid, $uid, $priority, $public, $password, $status, $input) {
		$this->pid = $pid;
		$this->uid = $uid;
		$this->priority = $priority;
		$this->public = $public;
		$this->password = $password;
		$this->status = $status;
		$this->input = $input;
	}
	public function checkVariables() {
		if(!preg_match('/^[0-9]+$/', $this->pid)) return false;
		if(!preg_match('/^[0-9]+$/', $this->uid)) return false;
		if(!preg_match('/^[0-9]$/', $this->priority)) return false;
		if(!preg_match('/^[0-1]$/', $this->public)) return false;
		if(!preg_match('/^[0-9a-zA-Z]{0,30}$/', $this->password)) return false;
		if(!preg_match('/^[0-5]$/', $this->status)) return false;
		return true;
	}
	private function getStatusStr($status) {
		if($status === 0) return 'Queueing - 排队中';
		if($status === 1) return 'Computing - 计算中';
		if($status === 1) return 'Done - 计算完成';
		if($status === 1) return 'Warning - 数据有误';
		if($status === 1) return 'Cancelled - 计算被取消';
		if($status === 1) return 'Error - 计算过程中发生系统错误';
	}
	private function getPluginName($pid) {
		require_once('plugin.class.php');
		$currentPlugin = new Plugin;
		$currentPlugin->pid = $pid;
		return json_decode($currentPlugin->getData(), true)['name'];
	}
	private function getUserName($uid) {
		require_once('user.class.php');
		$currentUser = new User;
		$currentUser->uid = $uid;
		return json_decode($currentUser->getData(), true)['username'];
	}
	public function getData(){
		if(($sqlCalculation = @mysql_query(
			'SELECT `cid`, `pid`, `uid`, `priority`, `public`, `password`, `status`,`input`, `result`
			FROM `calculation`
			WHERE `cid` = "'.$this->cid.'";')) === false) return false;
		if(($response = @mysql_fetch_assoc($sqlCalculation)) === false) return false;
		$response['cid'] = (int)$response['cid'];
		$this->pid = $response['pid'] = (int)$response['pid'];
			$response['pluginname'] = self::getPluginName($response['pid']);
		$this->uid = $response['uid'] = (int)$response['uid'];
			$response['username'] = self::getUserName($response['uid']);
		$this->priority = $response['priority'] = (int)$response['priority'];
		$this->public = $response['public'] = (bool)$response['public'];
		$this->password = $response['password'];
		$this->status = $response['status'] = (int)$response['status'];
			$response['statusStr'] = self::getStatusStr($response['status']);
		$this->input = $response['input'] = urldecode($response['input']);
		$this->result = $response['result'] = urldecode($response['result']);
		return json_encode($response);
	}
	public function listData($user=-1, $public=-1, $status=-1){
		$conditionStr = '';
		if($user == 0) {
			require_once('site.class.php');
			$uid = (int)Site::getSessionUid();
			$conditionStr .= 'AND(`uid` = "'.$uid.'")';
		}
		if($public == 0) $conditionStr .= 'AND(`public` = 1 AND `password` = "")';
		if($public == 1) $conditionStr .= 'AND(`public` = 1 AND `password` != "")';
		if($public == 2) $conditionStr .= 'AND(`public` = 0)';
		if($status != -1) $conditionStr .= 'AND(`status` = "'.$status.'")';
		if(strlen($conditionStr) > 3) {
			$conditionStr = substr($conditionStr, 3);
			$conditionStr = 'WHERE ' . $conditionStr;
		}
		if(($sqlCalculation = @mysql_query(
			'SELECT `cid`, `pid`, `uid`, `priority`, `public`, `password`, `status`, `input`, `result`
			FROM `calculation`'.$conditionStr.';')) === false) return false;
		$response = [];
		while(($item = @mysql_fetch_assoc($sqlCalculation)) !== false) {
			$item['cid'] = (int)$item['cid'];
			$item['pid'] = (int)$item['pid'];
			$item['uid'] = (int)$item['uid'];
			$item['priority'] = (int)$item['priority'];
			$item['public'] = (bool)$item['public'];
			$item['status'] = (int)$item['status'];
			$item['input'] = urldecode($item['input']);
			$item['result'] = urldecode($item['result']);
			$item['pluginname'] = self::getPluginName($item['pid']);
			$item['username'] = self::getUserName($item['uid']);
			$item['statusStr'] = self::getStatusStr($item['status']);
			array_push($response, $item);
		}
		return json_encode($response);
	}
	public function create(){
		if(($sqlCalculation = @mysql_query(
			'INSERT INTO `calculation`
				(`pid`, `uid`, `priority`, `public`, `password`, `status`, `input`)
			VALUES (
				"'.$this->pid.'",
				"'.$this->uid.'",
				"'.$this->priority.'",
				"'.$this->public.'",
				"'.$this->password.'",
				"'.$this->status.'",
				"'.urlencode($this->input).'"
			);')) === false) return false;
		return (int)mysql_insert_id();
	}
	public function modify(){
		if(($sqlCalculation = @mysql_query(
			'UPDATE `calculation`
			SET 
				`priority` = "'.$this->priority.'",
				`public` = "'.$this->public.'",
				`password` = "'.$this->password.'"
			WHERE `cid` = "'.$this->cid.'";')) === false) return false;
		return true;
	}
}
