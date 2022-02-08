<?php

namespace App\Interface;

interface UniqIdentifierGeneratorInterface {

    /**
     * Generate a uniq name
     * @return string
     */
    public function generate(): string;
}
