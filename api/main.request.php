<?php
require_once('../connection/connection.php');
require_once('globalSettings.conf.php');
require_once('publicFunctions.func.php');
session_start();

$request = json_decode(file_get_contents('php://input', 'r'), true);

require('Toro.php');
class user {
	function get() {}
	function post() {}
	function put() {}
	function delete() {}
}
Toro::serve(array(
	'/api/user/login' => 'user'
));
