<?php


/* * *****************************************
  --------------------------------------------
 * Copyrite © Caguct.com 2016
  --------------------------------------------
 * Author: Alexey Birukov aka CaguCT
  --------------------------------------------
 * All rights reserved
  --------------------------------------------
 * index.php - Switch file
  --------------------------------------------
 * ***************************************** */

/**
 * We establish the charset and level of errors
 */
header("Content-Type: text/html; charset=utf-8");
ini_set("display_errors", "1");
error_reporting(E_ALL);
session_start();

if( empty($_COOKIE['is_caguct']) ) die('В разработке');

define('ROOT', dirname(__FILE__));

require ROOT . '/libs/Functions.class.php';

require ROOT . '/libs/uCoz.class.php';
require ROOT . '/libs/Videokub.class.php';
require ROOT . '/libs/Smarty.class.php';

require ROOT . '/libs/Engine.class.php';
$engine = new Engine;
