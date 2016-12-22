<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/19/2016
 * Time: 9:47 AM
 */

namespace AppBundle\Api;

use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{

    protected function getFilters(Request $request)
    {
        if($request->get('filters')) {
            $filters = $request->get('filters');
            foreach ($filters as $key => &$filter) {
                if( strpos($key, 'active') !== false ) {
                    $filter = ( $filter == "true" )? true  : false;
                }

                if( strpos($key, 'id') !== false ) {
                    if( strpos($filter, "_") != 0) $filter = new \MongoId($filter);
                }
            }
            
            return $filters; 
        }
        
        return [];
    }

    protected function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            $apiError = new ApiErrorMessage(400, ApiErrorMessage::TYPE_INVALID_REQUEST_BODY_FORMAT);
            throw new ApiErrorException($apiError);
        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    protected function throwApiValidationException(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);
        $apiError = new ApiErrorMessage(400, ApiErrorMessage::TYPE_VALIDATION_ERROR);
        $apiError->set('errors', $errors);
        throw new ApiErrorException($apiError);
    }

    protected function createApiResponse($data, $statusCode = 200, SerializationContext $context = null)
    {
        $format = 'json';
        $json = $this->serialize($data, $format, $context);

        return new Response($json, $statusCode, array(
            'Content-Type' => 'application/json'
        ));
    }

    protected function serialize($data, $format = 'json', SerializationContext $context = null)
    {
        if($context == null ) {
            $context = new SerializationContext();
            $context->setSerializeNull(true);
            $context->setGroups(["Default"]);
        }

        return $this->container->get('jms_serializer')
            ->serialize($data, $format, $context);
    }
}