<?php
require_once('config.php');
require_once('database-factory.php');
require_once('transaction-info-factory.php');


// Define application version number
define( 'VERSION_NUMBER', '1.1.0.1' );

if(!check_configuration()) {
	die("One or more of Config variables are not set.");
}

/**
 * Check if a valid key exists
 */
if( !isset($_GET["id"]) && !in_array($_GET['key'], $GLOBALS['ACCESS_KEYS'], true)){
    http_response_code(403);
    die('Access Denied');
}
// Get all the transactions for which settlement time is null
$database_connection = new Database_Factory();
$unsettled_transaction_ids = $database_connection->getUnsettledTransactionIds();
if($unsettled_transaction_ids)
{
    $transaction_object = new Transaction_Info_Factory();
    $transaction_update_status = $transaction_object->updateUnsettledTransactions($unsettled_transaction_ids);
    echo '<dl>';
    foreach($transaction_update_status as $transaction => $status)
    {
        echo "<dt>$transaction</dt>";
        echo "<dd>$status</dd>";
    }
    echo '</dl>';
}
else
{
    echo " List of Unsettled transaction ids is empty";
}


 
 // Update the table with settlement information
/*
	Check to make sure all configuration variables are not empty
*/
function check_configuration()
{
    if( empty($GLOBALS['MERCHANT_LOGIN_ID'])
         || empty($GLOBALS['MERCHANT_TRANSACTION_KEY'])
         || !isset($GLOBALS['SERVER_CODE'])
         || empty($GLOBALS['DATABASE_DSN'])
         || empty($GLOBALS['DATABASE_USER'])
         || empty($GLOBALS['DATABASE_PASSWORD'])
         || empty($GLOBALS['ACCESS_KEYS'])
        )
        {
            return false;
        }
    return true;
}