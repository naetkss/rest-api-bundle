<?php

namespace RestApiBundle\EventSubscriber;

use RestApiBundle;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var RestApiBundle\Services\SettingsProvider
     */
    private $settingsProvider;

    public function __construct(RestApiBundle\Services\SettingsProvider $settingsProvider)
    {
        $this->settingsProvider = $settingsProvider;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onEvent'
        ];
    }

    public function onEvent(GetResponseForExceptionEvent $event)
    {
        if (!$this->settingsProvider->isRequestValidationExceptionHandlerEnabled()) {
            return;
        }

        $exception = $event->getException();

        if ($exception instanceof RestApiBundle\Exception\RequestModelMappingException) {
            $event
                ->setResponse(new JsonResponse(['properties' => $exception->getProperties()], 400));

            return;
        }
    }
}
