<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/19/2016
 * Time: 9:39 AM
 */

namespace AdministracionBundle\Controller;

use AdministracionBundle\Form\Material\EditMaterialForm;
use AdministracionBundle\Form\Material\NewMaterialForm;
use AppBundle\Api\BaseController;
use AdministracionBundle\Document\Material;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Materiales
 * @package AdministracionBundle\Controller
 * @Route("/materiales")
 * @Method("GET")
 */
class MaterialesController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("", name="materiales")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        if($request->get("codigo")) {
            $codigo = $this->generarCodigoMaterial();
            $data = ["codigo" => $codigo ];

            return $this->createApiResponse($data);
        }

        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $materiales = $dm->getRepository('AdministracionBundle:Material')
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);

        $data = ["materiales" => $materiales];

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($data, 200, $context);
    }

    /**
     * @param Material $material
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("/{id}", name="material_view")
     */
    public function viewAction(Material $material)
    {
        $context = new SerializationContext();
        $context->enableMaxDepthChecks();

        return $this->createApiResponse($material, 200, $context);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @Route("", name="material_new")
     */
    public function newAction(Request $request)
    {
        $material = new Material();
        $form = $this->createForm(NewMaterialForm::class, $material);
        $this->processForm($request, $form);

        if(!$form->isValid()) {
            $this->throwApiValidationException($form);
        }

        $material->setActive(true);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($material);
        $dm->flush();

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();

        return $this->createApiResponse($material, 201);
    }

    /**
     * @param Request $request
     * @param Material $material
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method({"PUT", "PATCH"})
     * @Route("/{id}", name="material_edit")
     */
    public function editAction(Request $request, Material $material)
    {
        $form = $this->createForm(EditMaterialForm::class, $material);
        $this->processForm($request, $form);

        if(!$form->isValid()) {
            $this->throwApiValidationException($form);
        }


        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($material);
        $dm->flush();

        return $this->createApiResponse($material);
    }

    /**
     * Genera codigo unico para el material de acuerdo
     * al tipo de material y aplicacion. De encontrar un material
     * con el codigo generado vuelve a intentarlo.
     *
     * @return string
     */
    public function generarCodigoMaterial()
    {
        $codigo = "M".random_int(1000, 9999);

        $dm = $this->get('doctrine_mongodb')->getManager();

        // Busca material con el codigo generado.
        $materiales = $dm->getRepository('AdministracionBundle:Material')->findBy(["codigo" => $codigo]);
        if(!empty($materiales)) {
            // Encontro material, intenta de nuevo.
            return $this->generarCodigoMaterial();
        }

        // No es necesario generar otro codigo, retorna el valor.
        return $codigo;
    }
}