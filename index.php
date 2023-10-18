<?php
require 'MonobankAPI.php';

$token = "utLXGjhCqnQzHtLuuP3LGmZ8WR3IwpS6CY_qsom_SoVc";

$request = new MonobankAPI($token);
$result = $request->requestClientInfo();
echo '<pre>';
print_r($result);
echo '</pre>';

$date1 = new DateTime('2023-07-13 10:30:00');
$date2 = new DateTime('2023-08-10 15:45:00');

//$result = $request->requestAccountTransactionsInfo("YFG_x1xqcAv727NnBIQMBg",$date1, $date2);
//echo '<pre>';
//print_r($result);
//echo '</pre>';

$result = $request->requestSelectedAccountsTransactionsInfo(["yovrndj0J3wkAuLM4CnoHw", "YFG_x1xqcAv727NnBIQMBg"],$date1, $date2);
echo '<pre>';
print_r($result);
echo '</pre>';

//$request->getAccount()->getTransactionsInfo ($data1, $data2);
