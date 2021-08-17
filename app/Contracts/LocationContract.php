<?php

namespace App\Contracts;

interface LocationContract
{
    public function getLatLon(string $address): array;
}
