<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelEventSubscriber
 */
class KernelEventSubscriber implements EventSubscriberInterface {

    /**
     * Manage the events
     * @return \array[][]
     */
    public static function getSubscribedEvents():array {
        return [
            // Defines the type of event to manage
            KernelEvents::EXCEPTION => [
                // Defined the methods and their priorities to be managed
                // highest priority === then run first
                ['displayKernelException', 255],
                ['onKernelException', 254],
                ['logKernelException', 1]
            ]
        ];
    }

    /**
     * Catch kernel exceptions
     * @param ExceptionEvent $event
     */
    public function displayKernelException(ExceptionEvent $event) {
        // Customize my new response object to display the exception details
        $response = new Response();
        $response->setContent("
            <h1>Type de requête non autorisée par le kernel!</h1>
        ");

        // Send the modified response object to the event
        $event->setResponse($response);
    }

    /**
     * Create new response with new message
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event) {

        $exception = $event->getRequest()->getRealMethod();
        // If the method is different from post ===> display error page (403)
        if($exception !== "POST") {
            $message = sprintf(
                'Code d\'erreur: %s',
                "403"
            );
            // Customize new response with a new message
            $response = new Response();
            $response->setContent("
                <h1>Type de requête non autorisée par le kernel!</h1>
                <p>$message</p>
            ");
            // Send the modified response object to the event
            $event->setResponse($response);
        }
    }

    /**
     * Return log exception in the file test_log.log
     * @param ExceptionEvent $event
     */
    public function logKernelException(ExceptionEvent $event) {
        $message = $event->getThrowable()->getMessage();
        file_put_contents( __DIR__ . '/../var/log/test_log.log', $message, FILE_APPEND);
    }
}