<?php
require_once('site.class.php');
switch ($action[1]) {
	case 'data':
		$response = [];
		require_once('article.class.php');
		$response['notices'] = json_decode(Article::listData(true), true);
		handle('0000'.json_encode($response));
		break;
	
	default:
		ERROR(ERROR_INPUT.'02', 'Request Error.');
		break;
}