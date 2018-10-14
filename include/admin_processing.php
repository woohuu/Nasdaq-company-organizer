<?php
include_once 'include/error_reporting.php';
require_once 'include/index_processing.php';

// declare and initialize variables
$symbol1 = '';
$companyname1 = '';
$stockprice = '';
$marketcap1 = '';
$capunit = '';
$sector1 = '';
$symbol2 = '';
$result1 = ''; // result resource for update search

$isUsearchFormSubmitted = false;
if (isset($_POST['usearchsubmit'])) {
    $isUsearchFormSubmitted = true;
}
// search found flag
if ($isUsearchFormSubmitted) {
    $symbol2 = $_POST['symbol2'];
    if ($symbol2) {
        $symbol2U = strtoupper($symbol2);
        $usearchquery = "select * from company_new where symbol = '$symbol2U'";
        $result1 = pg_query($db, $usearchquery);
        // populate data into the save form for editing
        if (pg_num_rows($result1) > 0) {
            $row = pg_fetch_assoc($result1);
            $symbol1 = $row['symbol'];
            $companyname1 = $row['companyname'];
            $stockprice = $row['lastsale'];
            $marketcap1 = trim($row['marketcap'], '$BM');
            $capunit = substr($row['marketcap'], -1);
            $sector1 = $row['sectorid'];
            $isRecordRetrieved = true;
        } else {
            echo "No matched company could be found.";
        }
    } else {
        echo "Please enter a symbol value.<br>";
    }
}

$isSaveFormSubmitted = false;
if (isset($_POST['savesubmit'])) {
    $isSaveFormSubmitted = true;
}

if ($isSaveFormSubmitted) {
    // get form posted data
    $symbol1 = $_POST['symbol1'];
    $companyname1 = $_POST['companyname1'];
    $stockprice = $_POST['stockprice'];
    $marketcap1 = $_POST['marketcap1'];
    $capunit = $_POST['capunit'];
    $sector1 = $_POST['sector1'];
    $mc = "$".$marketcap1.$capunit;
    
    $isSaveFormValid = false;
    // all fields are required to be filled to insert a record
    if ($symbol1 && $companyname1 && $stockprice && $marketcap1 && $capunit && $sector1) {
        $isSaveFormValid = true;
    } else {
        echo "Please enter every field.<br>";
    }

    if ($isSaveFormValid) {
        $symbol1U = strtoupper($symbol1);
        // check if $symbol1 is an existing symbol in table company_new
        $searchForSymbol1 = "select * from company_new where symbol = '$symbol1U'";
        if (pg_num_rows(pg_query($db, $searchForSymbol1)) > 0) {
            // existing symbol, update existing record
            $updatequery = "update company_new set companyname = '$companyname1',
            lastsale = '$stockprice', marketcap = '$mc', sectorid = '$sector1' 
            where symbol = '$symbol1U'";
            if (pg_query($db, $updatequery)) {
                echo "'$symbol1U' company record has been successfully updated.<br>";
            }
        } else { // create a new entry and insert it into db
        // form validation flag, initialized to be false
            $link = "https://www.nasdaq.com/symbol/".strtolower($symbol1);
            $insertquery = "insert into company_new (symbol, companyname, lastsale, 
                marketcap, sectorid, summarylink) values ('$symbol1U', '$companyname1', 
                '$stockprice', '$mc', '$sector1', '$link')";
            if (pg_query($db, $insertquery)) {
                echo "Your entry has been successfully saved into the database.";
            }
        }
    }
}

// handle delete
if (isset($_POST['deletesubmit'])) {
    $symbol1 = $_POST['symbol1'];
    if ($symbol1) {
        $symbol1U = strtoupper($symbol1);
        if(pg_query($db, "delete from company_new where symbol = '$symbol1U'")) {
            echo "'$symbol1U' company record has been deleted.";
        } else {
            echo "No matched company could be found.";
        }
    } else {
        echo "No company record is being selected.";
    }
}