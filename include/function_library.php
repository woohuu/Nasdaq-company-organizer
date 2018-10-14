<?php
include_once 'include/error_reporting.php';
require_once 'include/index_processing.php';

function displayResults($db_result) {
    $s = "</td><td>";
    while ($row = pg_fetch_assoc($db_result)) {
        echo "<tr><td>{$row['symbol']}{$s}{$row['companyname']}{$s}{$row['lastsale']}
            {$s}{$row['marketcap']}{$s}{$row['sectorname']}{$s}
            <a href={$row['summarylink']} target='_blank'>{$row['summarylink']}</a></td></tr>";
    }
}

function getSectorArrayFromDB($db) {
    $sectorArray = array();
    $result = pg_query($db, "select * from sector;");
    while ($row = pg_fetch_assoc($result)) {
        $sectorArray[$row['sectorid']] = $row['sectorname'];
    }
    return $sectorArray;
}
