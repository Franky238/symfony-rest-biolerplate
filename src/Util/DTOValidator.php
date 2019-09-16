<?php


namespace App\Util;

use App\Api\ApiProblem;
use App\Exception\ApiProblemException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class DTOValidator
{
    public static function validate(ConstraintViolationListInterface $errors)
    {
        if ($errors->count() > 0) {
            $apiProblem = new ApiProblem(Response::HTTP_BAD_REQUEST, ApiProblem::TYPE_VALIDATION_ERROR);

            $errorList = [];
            foreach ($errors as $error) {
                if ($error instanceof ConstraintViolationInterface) {
                    $errorList[$error->getPropertyPath()] = $error->getMessage();
                }
            }

            $apiProblem->set('errors', $errorList);

            throw new ApiProblemException($apiProblem);
        }

        return true;
    }

}