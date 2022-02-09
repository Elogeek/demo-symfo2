<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class KernelRequestEvent
 */
class KernelRequestEvent {

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelRequest(RequestEvent $event) {
        $exception = $event->getRequest()->getRealMethod();

        // If the method is different from post ===> display error 403
        if($exception !== "POST") {
            $message = sprintf(
                'Code d\'erreur: %s',
                "403"
            );

            // New response/message
            $response = new Response();
            $response->setContent("
                <h1>Type de requête non autorisée par le kernel!</h1>
                <p>$message</p>
            ");
            // Send the modified response object to the event
            $event->setResponse($response);
        }
    }

}
