<?php

class MonobankClient
{
    public ?string $clientId;
    public string $name;
    public string $webHookUrl;
    public string $permissions;
    public array $accounts;
    public ?array $jars;
}