<?php
namespace JohnConde\Authnet;
require 'config.php';
require 'vendor/stymiee/authnetjson/src/autoload.php';

class TransactionInfoFactory
{
//    private $settlement_datetime = null;
//    private $settlement_state = null;
//    private $invoice_number = null;
 //   private $transaction_info = null;
    
    
    public function __construct() {
        
    }   
    
    /*
     * For a given transaction id returns a JSON string of transaction information
     */
    
    public function getTransactionDetails($transaction_id)
    {
        if($this->checkConfiguration() && !empty($transaction_id))
        {
            $request  = AuthnetApiFactory::getJsonApiHandler($GLOBALS['MERCHANT_LOGIN_ID'], $GLOBALS['MERCHANT_TRANSACTION_KEY'], $GLOBALS['SERVER_CODE']);
            $response = $request->getTransactionDetailsRequest(array(
              'transId' => $transaction_id
            ));                     
 //           $this->transaction_info = $response;
//            setSettlementDatetime();
//            setInvoiceNumber();
//            setSettlementState();
            return $response;
        }
        return null;
    }
    
    public function checkConfiguration()
    {
         if(!empty($GLOBALS['MERCHANT_LOGIN_ID']) && !empty($GLOBALS['MERCHANT_TRANSACTION_KEY']) && !empty($GLOBALS['SERVER_CODE']))
             return true;
         
         return false;
    }
    
//    public function getSettlementDatetime()
//    {		
//        return $this->settlement_datetime;
//    }
//     public function setSettlementDatetime()
//    {	
//         if(!empty($transaction_info) && !empty($transaction_info->transaction) && !empty($transaction_info->transaction->batch) && !empty($transaction_info->transaction->batch->settlementTimeLocal))
//                $this->settlement_datetime = $transaction_info->transaction->batch->settlementTimeLocal;
//    }
//    public function getInvoiceNumber()
//    {		
//        return $this->invoice_number;
//    }
//     public function setInvoiceNumber()
//    {	
//        if(!empty($response) && !empty($response->transaction) && !empty($response->transaction->order) && !empty($response->transaction->order->invoiceNumber))
//            $this->invoice_number = $response->transaction->order->invoiceNumber;
//    }
//    public function getSettlementState()
//    {		
//        return $this->settlement_state;
//    }
//     public function setSettlementState()
//    {	
//         if(!empty($response) && !empty($response->transaction) && !empty($response->transaction->batch) && !empty($response->transaction->batch->settlementState))
//                $this->settlement_state = $response->transaction->batch->settlementState;
//    }
    
    
}
