<?php

namespace AppBundle\Controller;

use AppBundle\Document\Client;
use AppBundle\Form\Type\ClientType;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ClientController
 * @package AppBundle\Controller
 * @Route("/client")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ClientController extends Controller
{
    /**
     * @return array
     * @Route("/", name="clients")
     * @Template()
     */
    public function indexAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $clients = $dm->getRepository('AppBundle:Client')->findBy([
            "active" => true
        ]);

        return [
            "clients" => $clients
        ];
    }

    /**
     * @param Client $client
     * @return array
     * @Route("/show/{name}", name="client_show")
     * @Template()
     */
    public function showAction(Client $client)
    {
        $dm = $this->get("doctrine_mongodb")->getManager();
        return [
            "client" => $client
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @Route("/new", name="client_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ClientType::class);
        $form->handleRequest($request);

        if($form->isValid()) {
            /** @var Client $client */
            $client = $form->getData();
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($client);
            $client->setActive(true);


            $dm->flush();

            $this->addFlash("success", "Cliente {$client->getName()} creado con exito!");
            return $this->redirectToRoute("clients");
        }

        return [
            "form" => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param Client $client
     * @return array
     * @Route("/edit/{name}", name="client_edit")
     * @Template()
     */
    public function editAction(Request $request, Client $client)
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if($form->isValid()) {
            /** @var Client $client */
            $client = $form->getData();
            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($client);
            $dm->flush();

            $this->addFlash("success", "Client {$client->getName()} created!");
            return $this->redirectToRoute("clients");
        }

        return [
            "client" => $client,
            "form" => $form->createView()
        ];
    }

    /**
     * @param Client $client
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/disable/{name}", name="client_disable")
     */
    public function disableAction(Client $client)
    {
        $dm = $this->get("doctrine_mongodb")->getManager();
        $client->setActive(false);
        $dm->persist($client);
        $dm->flush();

        $this->addFlash("success", "Client {$client->getName()} removed succesfuly");
        return $this->redirectToRoute("clients");
    }

    /**
     * @param Client $client
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/enable/{name}", name="client_enable")
     */
    public function enableAction(Client $client)
    {
        $dm = $this->get("doctrine_mongodb")->getManager();
        $client->setActive(true);
        $dm->persist($client);
        $dm->flush();

        $this->addFlash("success", "Client {$client->getName()} enabled succesfuly");
        return $this->redirectToRoute("clients");
    }
}
