<?php

class Request
{
    private string $url;
    private array $headers;

    public function __construct(string $url, array $headers)
    {
        $this->url = $url;
        $this->headers = $headers;
    }

    public function sendRequest()
    {
        $state_ch = curl_init();
        curl_setopt($state_ch, CURLOPT_URL, $this->url);
        curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($state_ch, CURLOPT_HTTPHEADER, $this->headers);
        $state_result = curl_exec ($state_ch);
        $err = curl_error($state_ch);

        if ($err) {
            return "CURL Error #:" . $err;
        }
        $state_result = json_decode($state_result, true);
        return $state_result;
    }
}