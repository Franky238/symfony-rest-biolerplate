<?php


namespace App\Request;

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Exception\UnsupportedFormatException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RequestParamsToJson implements ParamConverterInterface {
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(ParamConverter $configuration)
    {
        if (!$configuration->getClass()) {
            return false;
        }

        // for simplicity, everything that has a "class" type hint is supported

        return true;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $class = $configuration->getClass();

        try {
            $object = $this->serializer->deserialize(
                $request->getContent(),
                $class,
                'json'
            );
        }
        catch (UnsupportedFormatException $e) {
            throw new NotFoundHttpException(sprintf('Could not deserialize request content to object of type "%s"',
                $class));
        }

        // set the object as the request attribute with the given name
        // (this will later be an argument for the action)
        $request->attributes->set($configuration->getName(), $object);
    }
}