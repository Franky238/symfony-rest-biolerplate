<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class AppStatusController
 * @package App\Controller
 *
 * @Rest\Route("/public/app")
 */
class AppStatusController extends AbstractFOSRestController {

    /**
     * @Rest\Get("/status", name="app_status")
     */
    public function indexAction()
    {
        return [
            "version" => "1.0.0",
        ];
    }
}
