<?php
require_once 'config.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Database_Factory{
    
    protected  $database_connection;
    
    public function __construct() {
        
            $this->init_database();
            
    }// end of constructor
  
/*
 *      Initialize database object
 */    
    
    public function init_database()
    {
        if($this->check_database_config())
        {
            try
            {
                $this->database_connection = new PDO( $GLOBALS['DATABASE_DSN'], $GLOBALS['DATABASE_USER'],$GLOBALS['DATABASE_PASSWORD'] );
                if (!$this->database_connection)
                    die('Something went wrong while connecting to the database'); // exit out 
            } catch(PDOException $e)
            {
                    die( 'ERROR: ' . $e->getMessage() );
            }
        }else
		 die(' Database configuration not set'); // exit out 
    }
    
/*
	Check database configuration variables exists
*/
    public function check_database_config()
	{
		if(!empty($GLOBALS['DATABASE_DSN'])  && !empty($GLOBALS['DATABASE_USER']) && !empty($GLOBALS['DATABASE_PASSWORD'] ))
			return 1;

		return 0;
	}  
        
/*
 *      Get transaction id's of all the transactions where  settlement date is null and transaction status is null from the database
 */
    public function getUnsettledTransactionIds()
    {
        
         $tsql = 'EXEC [usp_getTransactionAndSettlementStatus];';
         $query = $this->database_connection->prepare( $tsql );
         $query->execute();
         $transaction_ids = array();
         
         while ( $row = $query->fetch() ) {            
             $transaction_ids[] = $row['TransactionID']; 
         }
         
         return $transaction_ids;
    }
    
/*
 *      Update transaction settlement information for a given transaction id
 */
    public function updateUnsettledTransaction($transaction_detail)
    {
        extract($transaction_detail);
        if(!empty($transaction_id))
        {
            // Except Transaction id all other fields can have null value
            $transaction_status = !empty($transaction_status) ? $transaction_status : null;
            $settlement_time = !empty($settlement_time) ? $settlement_time : null;
            $invoice_number = !empty($invoice_number) ? $invoice_number : null;
//            $order_description = !empty($order_description) ? $order_description : null;
//            $card_number = !empty($card_number) ? $card_number : null;
//            $card_type = !empty($card_type) ? $card_type : null;
            $firstname = !empty($firstname) ? $firstname : null;
            $lastname = !empty($lastname) ? $lastname : null;
//            $address = !empty($address) ? $address : null;
//            $city = !empty($city) ? $city : null;
//            $state = !empty($state) ? $state : null;
//            $zip = !empty($zip) ? $zip : null;
//            $country = !empty($country) ? $country : null;
            
            $tsql = 'EXEC [usp_updateTransactionAndSettlementStatus]'
                    . '@TranStatus = :TransactionStatus,'
                    . '@SettleTime = :SettlementTime,'
                    . '@TransID = :TransactionID,'
                    . '@InvoiceNumber = :InvoiceNumber,'
                    //. '@Odesc = :OrderDescription,'
                    //. '@CardNum = :CardNumber,'
                    //. '@CardType = :CardType,'
                    . '@BillFName = :FirstName,'
                    . '@BillLName = :LastName;';
                    //. '@Address = :Address,'
                    //. '@City = :City,'
                    //. '@State = :State,'
                    //. '@Zip = :Zip,'
                    //. '@country = :Country ;';
                   
            
            $query = $this->database_connection->prepare( $tsql );
            $input_data = array( 'TransactionStatus' => $transaction_status ,
                                 'SettlementTime' => $settlement_time ,
                                 'TransactionID' => $transaction_id, 
                                 'InvoiceNumber' => $invoice_number,
                                 //'OrderDescription' => $order_description,
                                 //'CardNumber' => $card_number,
                                 //'CardType' => $card_type,
                                 'FirstName' => $firstname,
                                 'LastName' => $lastname 
                                 //'Address' => $address,
                                 //'City' => $city,
                                 //'State' => $state,
                                 //'Zip' => $zip,
                                 //'Country' => $country
                               );
          
             $result = $query->execute($input_data); 
             
             return $result;
        }
        return false;
    }
    
}

