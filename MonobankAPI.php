<?php

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
     * Returns an array containing the following information about the user:
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
     * @return array
     */
    public function requestUserInfo(): array
    {
        $addedUrl = "/personal/client-info";
        $url = $this->monobankURL . $addedUrl;
        $headers[] = "X-Token: $this->token";
        $state_ch = curl_init();
        curl_setopt($state_ch, CURLOPT_URL, $url);
        curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
        $state_result = curl_exec($state_ch);
        $err = curl_error($state_ch);
        if ($err) {
            echo "cURL Error #:" . $err;
            exit;
        }
        $state_result = json_decode($state_result, true);
        return $state_result;
    }

    private function validateDateTimeRange (DateTime $from, DateTime $to = null): array
    {
        $from = $from->format('Y-m-d H:i:s');
        if ($to) {
            $to = $to->format('Y-m-d H:i:s');
        }

        if (strtotime($from) < strtotime('-31 days -1 hour')) {
            return ['error' => 'The maximum time for which you can get a statement is 31 days and 1 hour'];
        }
        if ($from && $to) {
            if (strtotime($from) > strtotime($to)) {
                return ['error' => 'The end date must be greater than the start date'];
            }
        }
        if (!$to) {
            $to = date("Y-m-d H:i:s");
        }

        $from_unix = strtotime($from);
        $to_unix = strtotime($to);
        return [$from_unix, $to_unix];
    }

    /**
     * Gets account transaction information from the Monobank API.
     *
     * Returns an array containing the following information about each transaction:
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
     * * Counter name (if applicable)
     *
     * @param string $accountId The ID of the account to get transactions for.
     * @param DateTime $from The start date of the transaction range.
     * @param DateTime|null $to The end date of the transaction range.
     * @return array|string An array of transaction objects.
     */
    public function requestAccountTransactionsInfo(string $accountId, DateTime $from, DateTime $to = null ) :array|string
    {

        if ($to === null){
            $dateRange = $this->validateDateTimeRange($from);
        } else {
            $dateRange = $this->validateDateTimeRange($from, $to);
        }

        if (array_key_exists('error', $dateRange)){
            return $dateRange['error'];
        }

        $addedUrl = "/personal/statement/{$accountId}/{$dateRange[0]}/{$dateRange[1]}";
        $url = $this->monobankURL . $addedUrl;
        $headers[] = "X-Token: $this->token";
        $state_ch = curl_init();
        curl_setopt($state_ch, CURLOPT_URL,$url);
        curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
        $state_result = curl_exec ($state_ch);
        $err = curl_error($state_ch);

        if ($err) {
            return "CURL Error #:" . $err;
        }
        $state_result = json_decode($state_result, true);
        return $state_result;
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
            var_dump($account);
            $result[] = $this->requestAccountTransactionsInfo($account, $from, $to);
;
        }
        return $result;
    }
}