<?php
include_once 'include/error_reporting.php';
require_once 'include/function_library.php';
/*
// Database credentials
$host = "host = 127.0.0.1";
$port = "port = 5432";
$dbname = "dbname = companylist";
$credentials = "user = ec2-user password = test1234";

// Establish a connection with Postgresql
$db = pg_connect( "$host $port $dbname $credentials" );  
*/
$db = pg_connect(getenv("DATABASE_URL")); //Heroku db connection

if(!$db) {
    echo "Error : Unable to open database\n";
} 

// generate sector array for the dropdown form
$sectorArray = getSectorArrayFromDB($db);

// Declare and initialize variables
$symbol = '';
$companyname = '';
$min_price = '';
$max_price = '';
$marketcap = '';
$sector = '';
$query = '';
$result = '';

// Form processing flag
$isProcessingForm = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isProcessingForm = true;
}

if ($isProcessingForm) {
    
    $symbol = $_POST['symbol'];
    $companyname = $_POST['companyname'];
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];
    $marketcap = $_POST['marketcap'];
    $sector = $_POST['sector'];
    
    // form validation flag initialized to be true
    $isFormValid = true;
    
    // case of nothing inputed
    if (empty("{$symbol}{$companyname}{$min_price}{$max_price}{$marketcap}{$sector}")) {
        $isFormValid = false;
        echo "No input. Please enter at least one field.";
    }
    // symbol and company name are exclusive and only need one to search
    if (!empty($symbol) && !empty($companyname)) {
        $isFormValid = false;
        echo "Invalid search condition. Input either company symbol or company name, not both.\n";
    }
    if ($isFormValid) {
        if (!empty($symbol) && empty($companyname)) {  // search by symbol
            $symbolU = strtoupper($symbol);  // make it case-insensitive
            /* table company_new has only sectorid but not sectorname, has to do a inner join 
            with table secotr to display sectorname, same with all following queries */
            $query = "select * from (select * from company_new where symbol = '$symbolU') a 
                inner join sector b on a.sectorid = b.sectorid"; 
        } 
        elseif (empty($symbol) && !empty($companyname)) {  // search by company name
            $companynameU = strtoupper($_POST['companyname']);  // make it case-insensitive
            $query = "select * from (select * from company_new where upper(companyname) like '%$companynameU%') a 
                inner join sector b on a.sectorid = b.sectorid";
        } else {
            $where = ""; // search condition string for bounded search
            // build condition string based on user input
            if (!empty($min_price)) $where .= "and lastsale > $min_price "; // '.=' for string concatenation and assignment
            if (!empty($max_price)) $where .= "and lastsale < $max_price ";
            if ($marketcap == 'M') $where .= "and marketcap like '%M' ";
            if ($marketcap == 'B') $where .= "and marketcap like '%B' ";
            if (!empty($sector)) $where .= "and sectorid = $sector";
            if ($where) {
                $where = strstr($where, " ");  // remove the first word 'and'
                $query = "select * from (select * from company_new where $where) a
                    inner join sector b on a.sectorid = b.sectorid";
            }
        }
        $result = pg_query($db, $query);
    }
}
