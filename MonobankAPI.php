<?php

require "timeInterval.php";
require "Request.php";
require "objects/Transaction.php";
require "objects/MonobankClient.php";
require "objects/Account.php";

class MonobankAPI
{
    private $token;
    private $monobankURL = "https://api.monobank.ua";

    public function __construct(string $token)
    {
        $this->token = $token;
    }


    /**
     * Gets user information from the Monobank API.
     *
     * Returns an array of objects containing the following information about the user:
     *
     * * Client ID
     * * Name
     * * Webhook URL
     * * Permissions
     * * Accounts
     *
     * Accounts is an array of objects, each of which contains the following information:
     *
     * * ID
     * * Send ID
     * * Currency code
     * * Cashback type
     * * Balance
     * * Credit limit
     * * Masked PAN
     * * Type
     * * IBAN
     *
     * @return MonobankClient
     */
    public function requestClientInfo(): MonobankClient|string
    {
        $url = $this->monobankURL . "/personal/client-info";
        $headers[] = "X-Token: $this->token";
        $request = new Request($url, $headers);

        $requestResult = $request->sendRequest();
        if (array_key_exists("errorDescription", $requestResult)) {
            return "Too many requests";
        } else {
            $client = new MonobankClient();
            $client->clientId = $requestResult["clientId"]?? null;
            $client->name = $requestResult["name"];
            $client->webHookUrl = $requestResult["webHookUrl"];
            $client->permissions = $requestResult["permissions"];

            $clientAccounts = [];
            foreach ($requestResult["accounts"] as $key) {
                $account = new Account();
                $account->id = $key['id'] ?? null;
                $account->sendId = $key['sendId'] ?? null;
                $account->currencyCode = $key['currencyCode'] ?? null;
                $account->cashbackType = $key['cashbackType'] ?? null;
                $account->balance = $key['balance'] ?? null;
                $account->creditLimit = $key['creditLimit'] ?? null;
                $account->maskedPan = $key['maskedPan'] ?? [];
                $account->type = $key['type'] ?? null;
                $account->iban = $key['iban'] ?? null;

                $clientAccounts[] = $account;
            }
            $client->accounts = $clientAccounts;
            $client->jars = $requestResult["jars"] ?? null;

            return $client;
        }
    }



    /**
     * Gets account transaction information from the Monobank API.
     *
     * Returns an array of objects containing the following information about each transaction:
     * By default, the date up to which the transaction report is selected will use the current date and time.
     *
     * * ID
     * * Time
     * * Description
     * * MCC
     * * Original MCC
     * * Amount
     * * Operation amount
     * * Currency code
     * * Commission rate
     * * Cashback amount
     * * Balance
     * * Hold
     * * Receipt ID
     * * Counter name
     *
     * @param string $accountId The ID of the account to get transactions for.
     * @param DateTime $from The start date of the transaction range.
     * @param DateTime|null $to The end date of the transaction range.
     * @return array|string An array of transaction objects.
     */
    public function requestAccountTransactionsInfo(string $accountId, DateTime $from, DateTime $to = null ) :array|string
    {

        if ($to === null){
            $dateRange = getTimeInterval($from);
        } else {
            $dateRange = getTimeInterval($from, $to);
        }

        if (array_key_exists('error', $dateRange)){
            return $dateRange['error'];
        }

        $url = $this->monobankURL . "/personal/statement/{$accountId}/{$dateRange[0]}/{$dateRange[1]}";
        $headers[] = "X-Token: $this->token";
        $request = new Request($url, $headers);

        $requestResult= $request->sendRequest();

        if (array_key_exists("errorDescription", $requestResult)) {
            return "Too many requests";
        } else {
            $transactionArray = [];
            foreach ($requestResult as $transaction) {
                $transactionObject = new Transaction();
                $transactionObject->id = $transaction['id'] ?? null;
                $transactionObject->time = $transaction['time'] ?? null;
                $transactionObject->description = $transaction['description'] ?? null;
                $transactionObject->mcc = $transaction['mcc'] ?? null;
                $transactionObject->originalMcc = $transaction['originalMcc'] ?? null;
                $transactionObject->hold = $transaction['hold'] ?? null;
                $transactionObject->amount = $transaction['amount'] ?? null;
                $transactionObject->operationAmount = $transaction['operationAmount'] ?? null;
                $transactionObject->currencyCode = $transaction['currencyCode'] ?? null;
                $transactionObject->commissionRate = $transaction['commissionRate'] ?? null;
                $transactionObject->cashbackAmount = $transaction['cashbackAmount'] ?? null;
                $transactionObject->balance = $transaction['balance'] ?? null;
                $transactionObject->comment = $transaction['comment'] ?? null;
                $transactionObject->receiptId = $transaction['receiptId'] ?? null;
                $transactionObject->invoiceId = $transaction['invoiceId'] ?? null;
                $transactionObject->counterEdrpou = $transaction['counterEdrpou'] ?? null;
                $transactionObject->counterIban = $transaction['counterIban'] ?? null;
                $transactionObject->counterName = $transaction['counterName'] ?? null;

                $transactionArray[] = $transactionObject;
            }
            return $transactionArray;
        }
    }

    /**
     * Requests transaction information for multiple selected accounts from the Monobank API.
     *
     * This method retrieves transaction information for a list of selected accounts within the specified date range.
     *
     * @param array $selectedAccounts An array of account IDs for which transaction information will be requested.
     * @param DateTime $from The start date of the transaction range.
     * @param DateTime|null $to The end date of the transaction range. If null, the current date and time are used.
     *
     * @return array An array of transaction information for the selected accounts. Each element in the array represents the transactions for one account.
     */
    public function requestSelectedAccountsTransactionsInfo (array $selectedAccounts, DateTime $from, DateTime $to = null): array
    {
        $result = array();

        foreach ($selectedAccounts as $account) {
            $result[] = $this->requestAccountTransactionsInfo($account, $from, $to);
;
        }
        return $result;
    }
}