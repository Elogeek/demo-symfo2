<?php

namespace App\Service;

class FilenameGeneratorService {

    /**
     * Return a fake uniq filename
     * @return string
     */
    public function getUniqFilename(): string {
        return uniqid() . "png";
    }
}
