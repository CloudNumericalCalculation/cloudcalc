<?php
function ERROR($code, $errorMsg) {
	die(json_encode(array(
		'code' => $code,
		'errorMsg' => $errorMsg
	)));
}
function SUCCESS($data) { // $data is a json string
	die(json_encode(array(
		'code' => '0000',
		'response' => json_decode($data, true)
	)));
}

function getRequest($param) {
	global $request;
	return isset($request[$param]) ? $request[$param] : '';
}

function handle($response) {
	$code = substr($response, 0, 4);
	$msg = substr($response, 4);
	if($code === '0000') SUCCESS($msg);
	else {
		if($msg == '') {
			switch (substr($code, 0, 2)) {
				case ERROR_SYSTEM:
					$msg = 'System Error.';
					break;
				case ERROR_INPUT:
					$msg = 'Param Error.';
					break;
				case ERROR_PERMISSION:
					$msg = 'Permission Denied.';
					break;
				
				default:
					$msg = 'Error.';
					break;
			}
		}
		ERROR($code, $msg);
	}
}

/**
 * get current logged user's uid
 * 0 represents no user logged in
 * @return int uid
 */
function hasLogin() {
	require_once('site.class.php');
	return Site::getSessionUid();
}
/**
 * get current user's authority
 * @return int
 */
function getCurrentAuthority() {
	require_once('user.class.php');
	$currentUser = new User;
	$currentUser->uid = hasLogin();
	$userData = json_decode(substr($currentUser->getData(), 4), true);
	return $userData['authority'];
}
function checkAuthority($authority) {
	return (getCurrentAuthority() & $authority).'' === $authority.'';
}
function randomPassword() {
	$len = mt_rand(10, 20);
	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$pwd = '';
	for($i = 0; $i < $len; $i++) {
		$pwd .= $characters[mt_rand(0, strlen($characters)-1)];
	}
	return $pwd;
}