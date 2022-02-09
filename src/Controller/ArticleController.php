<?php
namespace App\Controller;

use App\Service\PlaceholderImageService;
use Doctrine\DBAL\Driver\OCI8\Exception\Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/articles', name: 'articles_')]
class ArticleController extends AbstractController {

    /**
     * List available articles
     * @return Response
     */
    #[Route('/', name: 'list')]
    public function list(): Response {
        $article = '
            <div>
                <p>Mon nom : test-article</p>
                <p>Mon prix :  50 euros</p>
                <p>Description: ceci est une description</p>
            </div>
        ';
        return new Response("<h1>Liste des articles</h1> $article");
    }

    /**
     * Add article
     * @param PlaceholderImageService $placehoderImage
     * @return Response
     */
    #[Route('/add', name: 'add')]
    public function add(PlaceholderImageService $placehoderImage): Response {
        try {
            $success = $placehoderImage->getNewImageAndSave(250,250,'article-default-img.png');
        }
        catch (Error $e) {
            $success =false;
        }
        if($success) {
            return new Response("<div>L'article est créé avec succès.</div>");
        }
        return new Response("<div>Une erreur en ajoutant l'article");
    }
}