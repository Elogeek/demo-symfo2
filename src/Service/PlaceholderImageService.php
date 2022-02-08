<?php
namespace App\Service;

use Doctrine\DBAL\Driver\OCI8\Exception\Error;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlaceholderImageService {

    private string $placeholderServiceProvideUrl = "https://via.placeholder.com/";
    private int $minimumImageWidth = 150;
    private int $minimumImageHeight = 150;

    /**
     * Return the downloaded image contents
     */
    public function getNewImageStream (int $imageWhidth, int $imageHeight) : string {
        if($imageWhidth < $this->minimumImageWidth || $imageHeight < $this->minimumImageHeight) {
            throw new Error("le format n'est pas bon");
        }
        $contents = file_get_contents("{$this->placeholderServiceProvideUrl}/{$imageWhidth} x {$imageHeight}");
        if(!$contents) {
            throw new Error("l'image n'a pas chargé");
        }
        return $contents;
    }

    /**
     * Download a new placeholder image and save it into the filesystem
     */
     public function getNewImageAndSave(int $imageWidth, int $imageHeight, string $filename): bool {
         $file = __DIR__ . "/../../uploads/$filename";
         $contents = $this->getNewImageStream($imageWidth, $imageHeight);
         $bytes = file_put_contents($file, $contents);
         return file_exists($file) && $bytes;
     }
}

