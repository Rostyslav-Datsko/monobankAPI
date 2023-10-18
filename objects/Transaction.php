<?php

class Transaction
{
    public string $id;
    public int $time;
    public string $description;
    public int $mcc;
    public int $originalMcc;
    public bool $hold; // null
    public int $amount;
    public int $operationAmount;
    public int $currencyCode;
    public ?int $commissionRate; // null
    public ?int $cashbackAmount; // null
    public int $balance;
    public ?string $comment;  // null
    public ?string $receiptId;  // null
    public ?string $invoiceId; // null
    public ?string $counterEdrpou; // null
    public ?string $counterIban; // null
    public ?string $counterName; // null

}
