<?php

namespace App\Contracts;

interface LocationContract
{
    public function callOutsideService(string $address): void;

    public function getLat(): float;

    public function getLon(): float;
}
