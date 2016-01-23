<?php
require_once('article.class.php');
switch ($action[1]) {
	case 'show':
		$currentArticle = new Article;
		$currentArticle->aid = getRequest('aid');
		if(!preg_match('/^[0-9]+$/', $currentArticle->aid)) handle(ERROR_INPUT.'01');
		$response = $currentArticle->getData();
		if($response === false) handle(ERROR_SYSTEM.'00'.'不存在！');
		$response = json_decode($response, true);
		if(!checkAuthority(9) && !$response['visibility']) handle(ERROR_SYSTEM.'00'.'不存在！');
		handle('0000'.json_encode($response));
		break;

	case 'list':
		handle('0000'.Article::listData());
		break;
	
	case 'new':
		if(!checkAuthority(9)) handle(ERROR_SYSTEM.'00');
		$currentArticle = new Article;
		$currentArticle->init(getRequest('title'), getRequest('content'), (int)getRequest('visibility'), (int)getRequest('notice'));
		if(!$currentArticle->checkVariables()) handle(ERROR_INPUT.'01');
		$response = $currentArticle->create();
		if($response === false) handle(ERROR_SYSTEM.'00');
		handle('0000{"aid":'.$response.'}');
		break;
	
	case 'renew':
		if(!checkAuthority(9)) handle(ERROR_SYSTEM.'00');
		$currentArticle = new Article;
		$currentArticle->aid = getRequest('aid');
		$response = json_decode($currentArticle->getData(), true);
		$currentArticle->init(getRequest('title'), getRequest('content'), getRequest('visibility'), getRequest('notice'));
		if(!$currentArticle->checkVariables()) handle(ERROR_INPUT.'01');
		$response = $currentArticle->modify();
		if($response === false) handle(ERROR_SYSTEM.'00');
		else handle('0000');
		break;
	
	default:
		ERROR(ERROR_INPUT.'02', 'Request Error.');
		break;
}