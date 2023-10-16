<?php
require 'MonobankAPI.php';

$token = "uWn6-n4y7oPAH64J1U9FYx6Xa5NP1dPM51hr_Gr-Q92Q";

$request = new MonobankAPI($token);
$result = $request->requestUserInfo();
echo '<pre>';
print_r($result);
echo '</pre>';

$date1 = new DateTime('2023-10-13 10:30:00');
$date2 = new DateTime('2023-09-15 15:45:00');
////
//$result = $request->requestAccountTransactionsInfo("Nn20ubBpWJdS8RXVMdL7fA",$date2);
//echo '<pre>';
//print_r($result);
//echo '</pre>';

$result = $request->requestSelectedAccountsTransactionsInfo(["yovrndj0J3wkAuLM4CnoHw", "YFG_x1xqcAv727NnBIQMBg"],$date2);
echo '<pre>';
print_r($result);
echo '</pre>';

//$request->getAccount()->getTransactionsInfo ($data1, $data2);
