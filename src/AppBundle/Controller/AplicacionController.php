<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/9/2016
 * Time: 6:42 PM
 */

namespace AppBundle\Controller;

use AppBundle\Form\Aplicacion\EditAplicacionForm;
use AppBundle\Form\Aplicacion\NewAplicacionForm;
use PanelBundle\Document\Aplicacion;
use PanelBundle\Document\Seccion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RecursosController
 * @package AppBundle\Controller
 * @Route("/aplicaciones")
 */
class AplicacionController extends Controller
{
    /**
     * @Template()
     * @Route("", name="aplicaciones_list")
     */
    function indexAction() {
        $dm = $this->get('doctrine_mongodb')->getManager();
        /** @var Aplicacion[] $aplicaciones */
        $aplicaciones = $dm->getRepository('PanelBundle:Aplicacion')->findAll();
        
        return [
            "aplicaciones" => $aplicaciones
        ];
    }

    /**
     * @Template()
     * @Route("/new", name="aplicaciones_new")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    function newAction(Request $request) {
        $form = $this->createForm(NewAplicacionForm::class);
        $form->handleRequest($request);

        if($form->isValid()) {
            /** @var Aplicacion $aplicacion */
            $aplicacion = $form->getData();

            /** @var Seccion $seccion */
            foreach($aplicacion->getSecciones() as &$seccion) {
                $seccion->setAplicacion($aplicacion);
            }

            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($aplicacion);
            $dm->flush();
            return $this->redirectToRoute("aplicaciones_list");
        }

        return [
            "form" => $form->createView()
        ];
    }

    /**
     * @Template()
     * @Route("/edit/{id}", name="aplicaciones_edit")
     * @param Aplicacion $aplicacion
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    function editAction(Aplicacion $aplicacion, Request $request) {
        $form = $this->createForm(EditAplicacionForm::class, $aplicacion);
        $form->handleRequest($request);

        if($form->isValid()) {
            /** @var Aplicacion $aplicacion */
            $aplicacion = $form->getData();

            /** @var Seccion $seccion */
            foreach($aplicacion->getSecciones() as &$seccion) {
                $seccion->setAplicacion($aplicacion);
            }

            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($aplicacion);
            $dm->flush();

            return $this->redirectToRoute("aplicaciones_list");
        }
        return [
            "form" => $form->createView()
        ];
    }
}