<?php
namespace App\Service;

use App\Interface\UniqIdentifierGeneratorInterface;
use Doctrine\DBAL\Driver\OCI8\Exception\Error;
use Hashids\Hashids;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlaceholderImageService {

    private string $saveDirectory;
    private UniqIdentifierGeneratorInterface $generator;
    private string $placeholderServiceProvideUrl = "https://via.placeholder.com/";
    private int $minimumImageWidth = 150;
    private int $minimumImageHeight = 150;
    private Hashids $hashids;

    /**
     * @param FilenameGeneratorService $generator
     * @param ParameterBagInterface $container
     * @param Hashids $hashids
     */
    public function __construct(UniqIdentifierGeneratorInterface $generator, ParameterBagInterface $container,Hashids $hashids) {
        $this->generator = $generator;
        $this->saveDirectory = $container->get("upload.directory");
        $this->hashids = $hashids;
    }

    /**
     * Return the downloaded image contents
     */
    public function getNewImageStream (int $imageWidth, int $imageHeight) : string {
        if($imageWidth < $this->minimumImageWidth || $imageHeight < $this->minimumImageHeight) {
            throw new Error("le format n'est pas bon, il est trop petit");
        }
        $contents = file_get_contents("{$this->placeholderServiceProvideUrl}/{$imageWidth} x {$imageHeight}");
        if(!$contents) {
            throw new \Error("l'image n'a pas chargé");
        }
        return $contents;
    }

    /**
     * Download a new placeholder image and save it into the filesystem
     */
     public function getNewImageAndSave(int $imageWidth, int $imageHeight): bool {
         $file = $this->saveDirectory . $this->generator->generate();
         $contents = $this->getNewImageStream($imageWidth, $imageHeight);
         $bytes = file_put_contents($file, $contents);
         return file_exists($file) && $bytes;
     }

}

