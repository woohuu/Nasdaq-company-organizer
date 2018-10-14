<?php
include_once 'include/error_reporting.php';
require_once 'include/index_processing.php';
require_once 'include/function_library.php';
require_once 'include/admin_processing.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="assets/css/index.css" type="text/css" />
    </head>
    <body>
        <div id="admin-header">
            <h1>Nasdaq company organizer admin page</h1>
        </div>
        <p>Warning: only database owner or admistrator is authorized to update the database.</p>
        <div id="u-search-form">
            <h4>Search for an existing company by symbol:</h4>
            <p>(You can skip this step if creating a new company)</p>
            <form id="usearch-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <input type="text" name="symbol2" value="<?php echo $symbol2; ?>"/><br>
                <input type="submit" name="usearchsubmit" value="Search"/>
            </form>
        </div>
        <div id="save-form">
            <h4>Create a new company/edit existing company info and save it into the organizer: </h4>
            <form id="save-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <label for="symbol">Symbol: </label>
                <input type="text" name="symbol1" value="<?php echo $symbol1; ?>"/><br><br>
                <label for="companyname">Company full name: </label>
                <input type="text" name="companyname1" value="<?php echo $companyname1; ?>"/><br><br>
                <label>Stock price ($): </label>
                <input type="text" name="stockprice" value="<?php echo $stockprice; ?>"/><br><br>
                <label for="marketcap">Market Cap: </label>
                <input type="text" name="marketcap1" value="<?php echo $marketcap1; ?>"/>
                <select name="capunit">
                    <option value="">Select unit</option>
                    <option value="M" <?php echo ($capunit == 'M')? 'selected':''; ?>>Million</option>
                    <option value="B" <?php echo ($capunit == 'B')? 'selected':''; ?>>Billion</option>
                </select>
                <label for="sector1">Sector: </label>
                <select name="sector1">
                    <option value="">Please select</option>
                    <?php
                    foreach ($sectorArray as $key => $value) {
                        if ($sector1 == $key) {
                            echo "<option value=$key selected>$value</option>"; // make form sticky
                        } else {
                            echo "<option value=$key>$value</option>";
                        }
                    } ?>
                </select>
                <br>
                <div id="btn">
                    <input type="submit" id="save-btn" name="savesubmit" value="Save"/>
                    <input type="submit" name="deletesubmit" value="Delete"/>
                </div>
            </form>
        </div>
    </body>
</html>