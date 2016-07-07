<?php
require 'config.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DatabaseFactory{
    
    protected  $database_connection;
    
    public function __construct() {
        
            $this->init_database();
            
    }// end of constructor
  
/*
 *      Initialize database object
 */    
    
    public function init_database()
    {
        if(check_database_config())
        {
            try
            {
                $this->database_connection = new PDO( $GLOBALS['DATABASE_DSN'], $GLOBALS['DATABASE_USER'],$GLOBALS['DATABASE_PASSWORD'] );
                if (!$this->database_connection)
                die('Something went wrong while connecting to the database'); // exit out 
            } catch(PDOException $e)
            {
                    echo 'ERROR: ' . $e->getMessage();
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
    
}

