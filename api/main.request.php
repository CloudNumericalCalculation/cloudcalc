<?php
require_once('../connection/connection.php');
require_once('globalSettings.conf.php');
require_once('publicFunctions.func.php');
session_start();

$request = json_decode(file_get_contents('php://input', 'r'), true);

