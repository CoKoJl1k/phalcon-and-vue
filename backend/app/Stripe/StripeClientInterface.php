<?php

namespace App\Stripe;

interface StripeClientInterface
{
    public function getCustomers(): array;
    public function getProducts(): array;
    public function getSubscriptions(): array;
}
