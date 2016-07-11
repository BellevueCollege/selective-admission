<?php
use JohnConde\Authnet as Anet;
require_once 'config.php';
require_once 'vendor/stymiee/authnetjson/src/autoload.php';
require_once 'database-factory.php';

class Transaction_Info_Factory
{
    public function __construct() {
      
    }   
    
    /*
     * For a given transaction id returns a JSON string of transaction information
     */
    
    function getTransactionDetails($transaction_id)
    {
        $transaction_details = array();
        if($this->checkConfiguration() && !empty($transaction_id))
        {
            $transaction_details["transaction_id"] = $transaction_id;
            $request  = Anet\AuthnetApiFactory::getJsonApiHandler($GLOBALS['MERCHANT_LOGIN_ID'], $GLOBALS['MERCHANT_TRANSACTION_KEY'], $GLOBALS['SERVER_CODE']);
            $response = $request->getTransactionDetailsRequest(array(
              'transId' => $transaction_id
            )); 
            if(!empty($response))
            {
                // TODO: Check $response->transaction is not empty
              
                if(!empty($response->transaction->transactionStatus))
                {                    
                    $transaction_details["transaction_status"] = $response->transaction->transactionStatus;
                }
                if(!empty($response->transaction->batch))
                {                   
                    if(!empty($response->transaction->batch->settlementTimeLocal))
                        $transaction_details["settlement_time"] = $response->transaction->batch->settlementTimeLocal;                   
                }      
                if(!empty($response->transaction->order))
                {
                    if(!empty($response->transaction->order->invoiceNumber))
                         $transaction_details["invoice_number"] = $response->transaction->order->invoiceNumber;
                    if(!empty($response->transaction->order->description))
                        $transaction_details["order_description"] = $response->transaction->order->description;
                }
                 if(!empty($response->transaction->payment)  )
                 {
                     if(!empty($response->transaction->payment->creditCard))
                     {
                        if(!empty($response->transaction->payment->creditCard->cardNumber))
                           $transaction_details["card_number"] = $response->transaction->payment->creditCard->cardNumber;
                        if( $response->transaction->payment->creditCard->cardType)
                            $transaction_details["card_type"] = $response->transaction->payment->creditCard->cardType;
                     }
                     
                     if(!empty($response->transaction->billTo))
                     {
                         if(!empty($response->transaction->billTo->firstName))
                              $transaction_details["firstname"] = $response->transaction->billTo->firstName;
                         if(!empty($response->transaction->billTo->lastName))
                              $transaction_details["lastname"] = $response->transaction->billTo->lastName;
                         if(!empty($response->transaction->billTo->address))
                              $transaction_details["address"] = $response->transaction->billTo->address;
                          if(!empty($response->transaction->billTo->city))
                              $transaction_details["city"] = $response->transaction->billTo->city;
                         if(!empty($response->transaction->billTo->state))
                              $transaction_details["state"] = $response->transaction->billTo->state;
                         if(!empty($response->transaction->billTo->zip))
                              $transaction_details["zip"] = $response->transaction->billTo->zip;
                         
                     }
                 }
            }
          
        }        
        return $transaction_details;
    }
    
   /*
    *  Get Unsettled transactions IDs
    */
    function updateUnsettledTransactions($unsettled_transaction_ids)
    {
        $transaction_update_status = array();
        if(!empty($unsettled_transaction_ids))
        {
            $database_connection = new Database_Factory();
            for($i=0;$i<count($unsettled_transaction_ids);$i++)
            {
                if(!empty($unsettled_transaction_ids[$i]))
                {
                    $transaction_detail =  $this->getTransactionDetails($unsettled_transaction_ids[$i]);
                    if(!empty($transaction_detail) )//&& isset($transaction_detail["transactionStatus"]) && isset($transaction_detail['settlement_datetime']))
                    {                        
                        $returned_value = $database_connection->updateUnsettledTransaction($transaction_detail);
                        if($returned_value)
                            $transaction_update_status[$unsettled_transaction_ids[$i]] = 'Update Successful';
                        else
                             $transaction_update_status[$unsettled_transaction_ids[$i]] = 'Update Unsuccessful';
                        // TODO write to a file if the transaction got updated or not
                    }
                }
            }
        }
        return $transaction_update_status;
    }   
    
    function checkConfiguration()
    {
         if(!empty($GLOBALS['MERCHANT_LOGIN_ID']) && !empty($GLOBALS['MERCHANT_TRANSACTION_KEY']) && !empty($GLOBALS['SERVER_CODE']))
             return true;
         
         return false;
    }     
}

