<?php

//namespace JohnConde\Authnet;
require 'config.php';
//require 'vendor/stymiee/authnetjson/src/autoload.php';


// Helper to get transaction id's from database for which their is no settlement information

// Define application version nuuber
define( 'VERSION_NUMBER', '1.0' );

if(!check_configuration())
	die("One or more of Config variables are not set.");

 
 // Update the table with settlement information
/*
	Check to make sure all configuration variables are not empty
*/
function check_configuration()
{	
	if(empty($GLOBALS['MERCHANT_LOGIN_ID']) && empty($GLOBALS['MERCHANT_TRANSACTION_KEY']) && !isset($GLOBALS['SERVER_CODE']))
                return false;
	if(empty($GLOBALS['DATABASE_DSN']) || empty($GLOBALS['DATABASE_USER']) || empty($GLOBALS['DATABASE_PASSWORD']))
		return false;
	if(empty($GLOBALS['BASE_URI']))
		return false;

	return true;
}