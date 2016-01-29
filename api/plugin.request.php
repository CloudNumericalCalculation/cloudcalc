<?php
require_once('plugin.class.php');
switch ($action[1]) {
	case 'show':
		$currentPlugin = new Plugin;
		$currentPlugin->pid = getRequest('pid');
		if(!preg_match('/^[0-9]+$/', $currentPlugin->pid)) handle(ERROR_INPUT.'01');
		$response = $currentPlugin->getData();
		if($response === false) handle(ERROR_SYSTEM.'00'.'不存在！');
		handle('0000'.$response);
		break;

	case 'list':
		handle('0000'.Plugin::listData(getRequest('uid')));
		break;
	
	case 'new':
		if(!checkAuthority(9)) handle(ERROR_PERMISSION.'00');
		$currentPlugin = new Plugin;
		$currentPlugin->init(getRequest('uid'), getRequest('folder'), getRequest('cover'), getRequest('name'), getRequest('author'), getRequest('git'), 0, 0);
		if(!$currentPlugin->checkVariables()) handle(ERROR_INPUT.'01');
		$response = $currentPlugin->create();
		if($response === false) handle(ERROR_SYSTEM.'00');
		handle('0000{"pid":'.$response.'}');
		break;
	
	case 'renew':
		if(!checkAuthority(9)) handle(ERROR_PERMISSION.'00');
		$currentPlugin = new Plugin;
		$currentPlugin->pid = getRequest('pid');
		$response = json_decode($currentPlugin->getData(), true);
		$gitStatus = (int)getRequest('gitStatus');
		if(!($gitStatus >= 0 && $gitStatus <= 2)) $gitStatus = $response['gitStatus'];
		$currentPlugin->init($response['uid'], $response['folder'], getRequest('cover'), getRequest('name'), getRequest('author'), getRequest('git'), $gitStatus, (int)getRequest('available'));
		if(!$currentPlugin->checkVariables()) handle(ERROR_INPUT.'01');
		$response = $currentPlugin->modify();
		if($response === false) handle(ERROR_SYSTEM.'00'.'目录名冲突！');
		else handle('0000');
		break;
	
	default:
		ERROR(ERROR_INPUT.'02', 'Request Error.');
		break;
}