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
    * Initialize database object
    */
    public function init_database()
    {
        try
        {
            $this->database_connection = new PDO(
                $GLOBALS['DATABASE_DSN'],
                $GLOBALS['DATABASE_USER'],
                $GLOBALS['DATABASE_PASSWORD']
            );

            if (!$this->database_connection)
            {
                die('Something went wrong while connecting to the database'); // exit out 
            }
                
        }
        catch(PDOException $e)
        {
            die( 'ERROR: ' . $e->getMessage() );
        }
    }

    /*
    *  Get transaction id's of all the transactions where  settlement date is null and transaction status is null from the database
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
    * Update transaction settlement information for a given transaction id
    */
    public function updateUnsettledTransaction($transaction_detail)
    {
        extract($transaction_detail);

        if(!empty($transaction_id))
        {
            
            $tsql = 'EXEC [usp_updateTransactionAndSettlementStatus]'
                    . '@TranStatus = :TransactionStatus,'
                    . '@SettleTime = :SettlementTime,'
                    . '@TransID = :TransactionID,'
                    . '@InvoiceNumber = :InvoiceNumber,'
                    . '@BillFName = :FirstName,'
                    . '@BillLName = :LastName;';

            $query = $this->database_connection->prepare($tsql);

            $input_data = array(
                'TransactionStatus' => $transaction_status,
                'SettlementTime'    => $settlement_time,
                'TransactionID'     => $transaction_id,
                'InvoiceNumber'     => $invoice_number,
                'FirstName'         => $firstname,
                'LastName'          => $lastname,
            );

             $result = $query->execute($input_data); 

             return $result;
        }
        return false;
    }
}

