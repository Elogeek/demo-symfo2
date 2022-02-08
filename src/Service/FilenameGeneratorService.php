<?php

namespace App\Service;

class FilenameGeneratorService {

    /**
     * Return a fake uniq filename
     */
    public function getUniqFilename(): string {
        return uniqid() . "png";
    }
}
