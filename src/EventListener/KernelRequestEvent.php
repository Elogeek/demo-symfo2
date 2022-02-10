<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class KernelRequestEvent
 */
class KernelRequestEvent {

    /**
     * Display page error
     * @param ExceptionEvent $event
     */
    public function onKernelRequest(ExceptionEvent $event) {
        $exception = $event->getRequest()->getRealMethod();

        // If the method is different from post ===> display error 403
        if($exception !== "POST") {
            $message = sprintf(
                'Code d\'erreur: %s',
                "403"
            );

            // New response with new message
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
