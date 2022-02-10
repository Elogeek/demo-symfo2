<?php

namespace App\EventSubscriber;

use App\Kernel;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelEventSubscriber
 */
class KernelEventSubscriber implements EventSubscriberInterface {

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

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
            ],

            // Check that the request is finished  (Event kernel.finish_request)
            KernelEvents::FINISH_REQUEST => [
                ['logIpAdress',0],
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
        file_put_contents( $_SERVER['DOCUMENT_ROOT']. '/var/log/test_log.log', $message, FILE_APPEND);
    }

    /**
     * Log client ip address in the file dev.log
     */
    public function logIpAdress(FinishRequestEvent $event) {
        if(!$event->isMainRequest()) {
            return;
        }
        $addressIp = $event->getRequest()->getClientIp();
        $this->logger->info('Request finished {"kernel.finish_request::logIpAddress()}', ['Request from'=>$addressIp]);
    }

}