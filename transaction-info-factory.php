<?php
use JohnConde\Authnet as Anet;
require_once 'config.php';
require __DIR__ . '/vendor/autoload.php';
require_once 'database-factory.php';

class Transaction_Info_Factory
{
    /*
     * For a given transaction id returns a JSON string of transaction information
     */
    function getTransactionDetails($transaction_id)
    {
        $transaction_details = array();

        $request = Anet\AuthnetApiFactory::getJsonApiHandler(
            $GLOBALS['MERCHANT_LOGIN_ID'],
            $GLOBALS['MERCHANT_TRANSACTION_KEY'],
            $GLOBALS['SERVER_CODE']
        );

        $response = $request->getTransactionDetailsRequest(
            array(
                'transId' => $transaction_id
            )
        ); 

        // Validate a response was returned
        if ($response->isSuccessful() && $response->messages->resultCode === 'Ok')
        {

            /**
             * Map response values to array
             */
            $transaction_details = [
                'transaction_id'     => ($transaction_id ?? null),
                'transaction_status' => ($response->transaction->transactionStatus ?? null),
                'settlement_time'    => ($response->transaction->batch->settlementTimeLocal ?? null),
                'invoice_number'     => ($response->transaction->order->invoiceNumber ?? null),
                'firstname'          => ($response->transaction->billTo->firstName ?? null),
                'lastname'           => ($response->transaction->billTo->lastName ?? null),
            ];

            /**
             * Return array
             */
            return $transaction_details;
        }
    }
    
    /*
    *  Get Unsettled transactions IDs
    */
    function updateUnsettledTransactions($unsettled_transaction_ids)
    {
        $transaction_update_status = array();
        $database_connection = new Database_Factory();

        foreach($unsettled_transaction_ids as $key => $transaction_id)
        {
            $transaction_detail =  $this->getTransactionDetails($transaction_id);

            if($transaction_detail)
            {
                $returned_value = $database_connection->updateUnsettledTransaction($transaction_detail);
                if($returned_value)
                    $transaction_update_status['TransactonID: '.$unsettled_transaction_ids[$key]] = 'Update Successful';
                else
                    $transaction_update_status['TransactonID: '.$unsettled_transaction_ids[$key]] = 'Update Unsuccessful';
            }
        }
        return $transaction_update_status;
    }

}

