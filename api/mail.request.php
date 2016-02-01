<?php
require_once('mail.func.php');
switch ($action[1]) {
	case 'feedback':
		$subject = '【用户反馈】CloudCalc';
		$body = '<h3>用户： ' . getRequest('basic')['name'] . '</h3>'
			.'<h3>联系方式：' . getRequest('basic')['contact'] . '</h3>'
			.'<hr>'
			.'反馈内容:' . '<br>'
			.'<pre>' . getRequest('feedback') . '</pre>';
		// echo $subject;
		// echo $body;
		if(sendNotificationEmail($subject, $body)) handle('0000');
		else handle(ERROR_SYSTEM.'00');
		break;
	
	case 'plugin':
		$subject = '【插件提交】CloudCala';
		$body = '<h3>用户： ' . getRequest('basic')['name'] . '</h3>'
			.'<h3>联系方式：' . getRequest('basic')['contact'] . '</h3>'
			.'<hr>'
			.'提交用户：' . getRequest('plugin')['username'] . '<br>'
			.'插件名字：' . getRequest('plugin')['name'] . '<br>'
			.'插件作者：' . getRequest('plugin')['author'] . '<br>'
			.'插件Git地址：' . getRequest('plugin')['git'] . '<br>';
		// echo $subject;
		// echo $body;
		if(sendNotificationEmail($subject, $body)) handle('0000');
		else handle(ERROR_SYSTEM.'00');
		break;
	
	default:
		ERROR(ERROR_INPUT.'02', 'Request Error.');
		break;
}