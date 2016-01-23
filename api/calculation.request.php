<?php
require_once('calculation.class.php');
switch ($action[1]) {
	case 'show':
		$currentCalculation = new Calculation;
		$currentCalculation->cid = getRequest('cid');
		if(!preg_match('/^[0-9]+$/', $currentCalculation->cid)) handle(ERROR_INPUT.'01');
		$response = $currentCalculation->getData();
		if($response === false) handle(ERROR_SYSTEM.'00'.'不存在！');
		$response = json_decode($response, true);
		require_once('site.class.php');
		$uid = Site::getSessionUid();
		if($response['uid'] == $uid || checkAuthority(9)) {
			handle('0000'.json_encode($response));
		}
		if(!$response['public']) handle(ERROR_PERMISSION.'01'.'该用户没有公开计算内容！');
		if($response['public'] && $response['password'] == getRequest('password')) {
			handle('0000'.json_encode($response));
		}
		else {
			handle(ERROR_PERMISSION.'02'.'查看密码错误，请重新输入！');
		}
		handle(ERROR_PERMISSION.'00');
		break;

	case 'list':
		handle('0000'.Calculation::listData(getRequest('uid')));
		break;
	
	case 'new':
		$currentCalculation = new Calculation;
		require_once('site.class.php');
		$uid = Site::getSessionUid();
		if($uid == 0) handle(ERROR_PERMISSION.'01'.'请先登陆！');
		require_once('user.class.php');
		$currentUser = new User;
		$currentUser->uid = $uid;
		$response = json_decode($currentUser->getData(), true);
		$priority = $response['level'];
		if($priority  != 9) {
			require_once('plugin.class.php');
			$currentPlugin = new Plugin;
			$currentPlugin->pid = getRequest('pid');
			$response = json_decode($currentPlugin->getData(), true);
			if($uid == $response['uid']) $priority = 5;
		}
		$currentCalculation->init(getRequest('pid'), $uid, $priority, 0, '', 0, getRequest('input'));
		if(!$currentCalculation->checkVariables()) handle(ERROR_INPUT.'01');
		$response = $currentCalculation->create();
		if($response === false) handle(ERROR_SYSTEM.'00');
		handle('0000{"cid":'.$response.'}');
		break;
	
	case 'renew':
		$currentCalculation = new Calculation;
		$currentCalculation->cid = getRequest('cid');
		$response = json_decode($currentCalculation->getData(), true);
		require_once('site.class.php');
		$uid = Site::getSessionUid();
		if($uid == 0) handle(ERROR_PERMISSION.'00'.'请先登陆！');
		if($response['uid'] != $uid && !checkAuthority(9)) handle(ERROR_PERMISSION.'00');
		$priority = $response['priority'];
		if(checkAuthority(9)) $priority = getRequest('priority');
		$currentCalculation->init($response['pid'], $response['uid'], $priority, (int)getRequest('public'), getRequest('password'), $response['status'], $response['input']);
		if(!$currentCalculation->checkVariables()) handle(ERROR_INPUT.'01');
		$response = $currentCalculation->modify();
		if($response === false) handle(ERROR_SYSTEM.'00');
		else handle('0000');
		break;
	
	default:
		ERROR(ERROR_INPUT.'02', 'Request Error.');
		break;
}