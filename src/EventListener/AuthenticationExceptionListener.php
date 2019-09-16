<?php

namespace App\EventListener;

use Auth0\SDK\Exception\CoreException;
use Auth0\SDK\Exception\InvalidTokenException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AuthenticationExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $code = 500;

        if ($exception instanceof CoreException) {
            $code = 401;
        }

        if ($exception instanceof InvalidTokenException) {
            $code = 401;
        }

        if ($exception instanceof NotFoundHttpException) {
            $code = 404;
        }

        $ex = $exception->getPrevious();
        if ($ex instanceof AccessDeniedException) {
            if ($ex->getAttributes()[0] !== "IS_AUTHENTICATED_ANONYMOUSLY"
                && $ex->getAttributes()[0] !== "ROLE_OAUTH_AUTHENTICATED"
                &&  strlen($event->getRequest()->headers->get("Authorization")) > 0)
                $code = 403;
            else
                $code = 401;
        }

        $responseData = [
            'error' => [
                'code' => $code,
                'message' => $exception->getMessage()
            ]
        ];

        $event->setResponse(new JsonResponse($responseData, $code));
    }
}