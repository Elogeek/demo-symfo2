<?php

namespace App\Service;

use App\Interface\UniqIdentifierGeneratorInterface;

class TokenGeneratorService implements UniqIdentifierGeneratorInterface {

    public function generate(): string {

        return 'random generated token';
    }
}