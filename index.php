<?php
include_once 'include/error_reporting.php';
require_once 'include/index_processing.php';
require_once 'include/function_library.php';

?>

<!DOCTYPE html>
<html>
    <div id="wrapper">
        <head>
            <title>Nasdaq Company Organizer</title>
            <link rel="stylesheet" href="assets/css/index.css" type="text/css" />
            <script type="text/javascript" src="assets/js/index.js"></script>
        </head>
        <body>
            <div id="my-header">
                <h1>Welcome to Nasdaq company organizer</h1>
            </div>
            <div id="user-guide">
                <p>Search for a public company listed on <a href="https://www.nasdaq.com" target='_blank'>Nasdaq</a> by the following conditions:</p>
                <ul id="search-con">
                    <li>Company symbol (e.g., AAPL for Apple Inc.)</li>
                    <li>Company name (keyword acceptable)</li>
                    <li>Stock price ($)</li>
                    <li>Market capitalization</li>
                    <li>Sector</li>
                </ul>
                <p>User guide:</p>
                <ul id="search-type">
                    <li>Targeted search: input company symbol or company name (all other conditions will be ignored).</li>
                    <li>Bounded search: use a combination of stock price, market cap and sector to 
                    return all companies satisfying specified conditions.</li>
                </ul>
                <button id="admin-btn">Administrator Entrance</button>
            </div>
    
            <div id="search-form">
                <form id="my-form" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                    <label for="symbol">Symbol: </label>
                    <input type="text" name="symbol" value="<?php echo $symbol; ?>"/><br><br>
                    <label for="companyname">Company name: </label>
                    <input type="text" name="companyname" value="<?php echo $companyname; ?>"/><br><br>
                    <label>Stock price </label><br>
                    <label for="min_price">larger than (>): </label>
                    <input type="text" name="min_price" value="<?php echo $min_price; ?>"/>
                    <label for="max_price">lower than (<): </label>
                    <input type="text" name="max_price" value="<?php echo $max_price; ?>"/><br><br>
                    <label for="marketcap">Market Cap: </label>
                    <select name="marketcap">
                        <option value="">Please select</option>
                        <option value="M" <?php echo ($marketcap == 'M')? 'selected':''; ?>>less than $1 billion</option>
                        <option value="B" <?php echo ($marketcap == 'B')? 'selected':''; ?>>larger than $1 billion</option>
                    </select>
                    <label for="sector">Sector: </label>
                    <select name="sector">
                        <option value="">Please select</option>
                        <?php
                        foreach ($sectorArray as $key => $value) {
                            if ($sector == $key) {
                                echo "<option value=$key selected>$value</option>"; // make form sticky
                            } else {
                                echo "<option value=$key>$value</option>";
                            }
                        } ?>
                    </select>
                    <br>
                    <div id="btn">
                        <input type="submit" id="search-btn" name="searchsubmit" value="Search"/>
                    </div>
                    <br><br>
                </form>
            </div>
            <div id="search-result">
                <?php
                    if (!empty($result)) {
                        if(pg_num_rows($result) > 0) {
                            $thead = "<tr><th>Symbol</th><th>Company Name</th><th>Last Price</th>
                                    <th>Market Cap</th><th>Sector</th><th>Summary Link</th></tr>";
                            echo "<h3>Search Results:</h3><br><table>{$thead}";
                            displayResults($result);
                            echo "</table>";
                        } else {
                            echo "No matched company could be found.<br>";
                        }
                    }
                ?>
            </div>
        </div>
    </body>

</html>