<?php
require_once('user.class.php');
// var_dump(password_hash(md5('admin' . md5('admin') . '.cc'), PASSWORD_BCRYPT));
// var_dump(password_verify(md5('admin' . md5('admin') . '.cc'), password_hash(md5('admin' . md5('admin') . '.cc'), PASSWORD_BCRYPT)));
require_once('site.class.php');
switch ($action[1]) {
	case 'data':
		$uid = Site::getSessionUid();
		if($uid == 0) handle('0000'.json_encode(array('signin' => false, 'level' => 0)));
		$currentUser = new User;
		$currentUser->uid = $uid;
		$response = json_decode($currentUser->getData(), true);
		$response['signin'] = true;
		unset($response['password']);
		handle('0000'.json_encode($response));
		break;
	case 'signin':
		Site::clearSession();
		$username = getRequest('username');
		if(!preg_match('/^[a-zA-Z][a-zA-Z0-9]{4,29}$/', $username)) handle(ERROR_INPUT.'01');
		$currentUser = new User;
		$currentUser->uid = User::getUidByUsername($username);
		$response = json_decode($currentUser->getData(), true);
		// var_dump($response);
		$password = getRequest('password');
		if(!password_verify(md5($username.$password.'.cc'), $response['password'])) {
			handle(ERROR_PERMISSION.'01'.'用户名和密码不匹配');
		}
		Site::writeInSession($currentUser->uid); handle('0000');
		break;
	case 'signout':
		Site::clearSession(); handle('0000');
		break;
	case 'show':
		$currentUser = new User;
		$currentUser->uid = getRequest('uid');
		if(!preg_match('/^[0-9]+$/', $currentUser->uid)) handle(ERROR_INPUT.'01');
		$response = $currentUser->getData();
		if($response === false) handle(ERROR_SYSTEM.'00'.'不存在！');
		handle('0000'.$response);
		break;
	case 'list':
		handle('0000'.User::listData(getRequest('username')));
		break;
	case 'signup':
		$currentUser = new User;
		$username = getRequest('username');
		if(!preg_match('/^[a-zA-Z][a-zA-Z0-9]{4,29}$/', $username)) handle(ERROR_INPUT.'01');
		$existUid = User::getUidByUsername($username);
		if($existUid != 0) handle(ERROR_PERMISSION.'02'.'用户名已存在！');
		$password = password_hash(md5($username.getRequest('password').'.cc'), PASSWORD_BCRYPT);
		$currentUser->init(getRequest('username'), $password, getRequest('email'));
		if(!$currentUser->checkVariables()) handle(ERROR_INPUT.'02');
		$response = $currentUser->create();
		if($response === false) handle(ERROR_SYSTEM.'00');
		handle('0000{"uid":'.$response.'}');
		break;
	case 'renew':
		if(Site::getSessionUid() !== getRequest('uid') && !checkAuthority(9)) handle(ERROR_PERMISSION.'01');
		$currentUser = new User;
		$currentUser->uid = getRequest('uid');
		$response = json_decode($currentUser->getData(), true);
		if(!password_verify(md5($response['username'].getRequest('password_old').'.cc'), $response['password'])) handle(ERROR_PERMISSION.'02'.'密码错误！');
		$password_new = getRequest('password_new');
		if($password_new === '') $password_new = getRequest('password_old');
		$password_new = password_hash(md5($response['username'].$password_new.'.cc'), PASSWORD_BCRYPT);
		$currentUser->init($response['username'], $password_new, $response['email'], $response['level']);
		if(!$currentUser->checkVariables()) handle(ERROR_INPUT.'01');
		$response = $currentUser->modify();
		if($response === false) handle(ERROR_SYSTEM.'00');
		else handle('0000');
		break;
	default:
		ERROR(ERROR_INPUT.'02', 'Request Error.');
		break;
}