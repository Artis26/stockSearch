<?php
require_once(__DIR__ . '/vendor/autoload.php');

$config = Finnhub\Configuration::getDefaultConfiguration()->setApiKey('token', 'c82lk42ad3ia12596g4g');
$client = new Finnhub\Api\DefaultApi(
    new GuzzleHttp\Client(),
    $config
);

$currentTime = time();
$yesterday = $currentTime - ((24*60*60));

if ($_GET['search'] == null) {
    $symbols = ['AAPL', 'AMZN', 'MSFT', 'TSLA'];
} else {
    $symbols = [$_GET['search']];
}
?>

<form method="get" action="/">
    <input name="search" value=""/>
    <button type="submit">Submit</button>
</form>

<table border="1|0">
    <thead>
        <th>
            Name
        </th>
        <th>
            Symbol
        </th>
        <th>
            Price
        </th>
        <th>
            Up or Down
        </th>
    </thead>
    <?php foreach ($symbols as $one) {
        ($stockSymbolName = $client->symbolSearch($one)->getResult());
        $stockName = $stockSymbolName[0]['description'];

        $stockInfo = $client->stockCandles($one, "D", $currentTime - (24*60*60), $currentTime);
        $stockYesterday = $stockInfo['o'][0];
        $stockToday = $stockInfo['o'][1];
        $stockDiff = $stockYesterday - $stockToday;?>
                <tr>
        <td>
            <?php echo $stockName;  ?>
        </td>
        <td>
            <?php echo $one;  ?>
        </td>
        <td>
            <?php echo round($stockToday,2);  ?>
        </td>
        <?php if ($stockDiff >= 0 ) {  ?>
        <td style="background-color: #7CFC00">
            <?php echo round($stockDiff,2);  ?>
            <?php } else { ?>
        <td style="background-color: #FF0000">
            <?php echo round($stockDiff,2);  ?>
        </td>
    </tr>
        <?php } ?>
    <?php } ?>
</table>
<br>
<form method="get" action="/">
    <button type="submit" value="<?php $symbols = ['AAPL', 'AMZN', 'MSFT', 'TSLA']?>" >Reset</button>
</form>
