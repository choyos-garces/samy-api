<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/13/2016
 * Time: 1:14 PM
 */

namespace InventarioBundle\Controller;

use AdministracionBundle\Document\Bodega;
use AdministracionBundle\Document\Material;
use AdministracionBundle\Document\Productor;
use AdministracionBundle\Document\Proveedor;
use AppBundle\Api\ApiErrorException;
use AppBundle\Api\ApiErrorMessage;
use AppBundle\Api\BaseController;
use InventarioBundle\Document\GuiaTransferencia;
use InventarioBundle\Document\InventarioBodega;
use InventarioBundle\Document\InventarioMaterial;
use InventarioBundle\Document\MovimientoDetalle;
use InventarioBundle\Document\MovimientoInventario;
use InventarioBundle\Document\MovimientoMaterial;
use InventarioBundle\Form\NewMovimientosForm;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MovimientosController
 * @package InventarioBundle\Controller
 * @Route("/movimientos")
 */
class MovimientosController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("", name="movimientos_lista")
     */
    public function indexAction(Request $request)
    {
        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $movimientos = $dm->getRepository('InventarioBundle:MovimientoInventario')
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);

        $data = ["movimientos" => $movimientos];

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($data, 200, $context);
    }

    /**
     * @param MovimientoInventario $movimientoInventario
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("/{id}", name="movimientos_view")
     */
    public function viewAction(MovimientoInventario $movimientoInventario)
    {
        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($movimientoInventario, 200, $context);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @Route("", name="movimiento_new")
     */
    public function newAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $form = $this->createForm(NewMovimientosForm::class);
        $this->processForm($request, $form);

        if(!$form->isValid()) {
            $this->throwApiValidationException($form);
        }

        /** @var MovimientoInventario $movimiento */
        $movimiento = $form->getData();

        /**********************************************************************************************************/
        $user = $dm->getRepository('AppBundle:User')->findOneBy(['email' => 'admin@samy.com']);
        $movimiento->setCreatedBy($user);
        /**********************************************************************************************************/

        $this->processMovimientoDetalle($movimiento->getDetalle(), $movimiento);

        $errores = [];
        $inventarios = [];
        foreach ( $movimiento->getMateriales() as &$movimientoMaterial)
        {
            //Prepara el objetto MovimientoMaterial.
            $movimientoMaterial->setBodega($movimiento->getBodega());
            $movimientoMaterial->setAccion($movimiento->isAccion());

            //Busco en inventario existente del mismo.
            $inventario = $this->fetchInventarioMaterial($movimientoMaterial->getMaterial());

            //Reviso si el movimiento puede ser efectuado.
            if( $this->isMovimientoMaterialPermitido($movimientoMaterial, $movimiento->getDetalle(), $inventario, $errores) ) {
                //Realiza los movimientos.
                $this->handleMovimiento($movimientoMaterial, $inventario, $movimiento->getDetalle());
                $movimientoMaterial->setMovimiento($movimiento);

                //Guarda el inventario de ese material para persistir a la base de datos al final.
                $inventarios[] = $inventario;
            }
        }


        //Si hubo algun error con algun material, lanza una excepcion.
        if( !empty($errores) ) {
            $this->throwInventarioValidationError($errores);
        };

        //Persiste el movimiento
        $dm->persist($movimiento);

        //Actualiza el inventario
        $this->updateInventarioBodegas($inventarios);

        // Movimiento necesita una guia de transferencia?
        $this->handleGuias($movimiento);

        $dm->flush();

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($movimiento, 201, $context);
    }

    /**
     * Devuelve el inventario del material en la bodega escogida.
     * Si no se a realizado ningun movimeinto con ese material,
     * devuelve un Inventario con cantidad vacia.
     *
     * @param Material $material
     * @return InventarioMaterial
     */
    private function fetchInventarioMaterial(Material $material)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        /** @var InventarioBodega $inventario */
        $inventario = $dm->getRepository('InventarioBundle:InventarioMaterial')->findOneBy([
            "material" => $material
        ]);

        //Si todavia no existe un ingreso, inicia uno para el material.
        if( $inventario == null ) {
            $inventario = new InventarioMaterial();
            $inventario->setMaterial($material);
            $inventario->setCantidad(0);
        }

        return $inventario;
    }

    /**
     * @param InventarioMaterial $inventarioMaterial
     * @param Bodega $bodega
     * @return InventarioBodega
     */
    private function getInventarioEnBodega(InventarioMaterial $inventarioMaterial, Bodega $bodega )
    {
        /** @var InventarioBodega $inventarioBodega */
        foreach ($inventarioMaterial->getInventarioBodegas() as $inventarioBodega) {
            if($inventarioBodega->getBodega()->getId() == $bodega->getId()) return $inventarioBodega;
        }

        $inventarioBodega = new InventarioBodega();
        $inventarioBodega->setMaterial($inventarioMaterial->getMaterial());
        $inventarioBodega->setBodega($bodega);
        $inventarioBodega->setCantidad(0);
        $inventarioBodega->setSaldoInicial(false);
        $inventarioBodega->setInventarioMaterial($inventarioMaterial);

        $inventarioMaterial->addInventarioBodega($inventarioBodega);

        return $inventarioBodega;
    }

    /**
     * @param MovimientoMaterial $movimientoMaterial
     * @param MovimientoDetalle $detalle
     * @param InventarioMaterial $inventario
     * @param [] $errores
     * @return bool
     */
    private function isMovimientoMaterialPermitido(MovimientoMaterial $movimientoMaterial, MovimientoDetalle $detalle, InventarioMaterial $inventario, &$errores)
    {
        // Establece nombre del material a usar
        $nombreMaterial = "(#{$movimientoMaterial->getMaterial()->getCodigo()}) {$movimientoMaterial->getMaterial()->getNombre()}";
        $inventarioEnBodega = $this->getInventarioEnBodega($inventario, $movimientoMaterial->getBodega());

        // True : Ingreso; Falso : Egreso
        if( $movimientoMaterial->isAccion() == MovimientoMaterial::INVENTARIO_INGRESO ) {

            // Es saldo Inicial? Existen Un Inventario
            if( $detalle->getDocumento() == MovimientoDetalle::SALDO_INICIAL && $inventarioEnBodega->hasSaldoInicial() == true ) {
                $errores["materiales"][] = "Saldo Inicial para el material {$nombreMaterial} ya ha sido ingresado.";
                return false;
            }

            return true;
        }
        else {
            // Hay material disponible?
            if($inventarioEnBodega->getCantidad() < $movimientoMaterial->getCantidad() ) {
                $errores["materiales"][] = "Inventario de el material {$nombreMaterial} es insifuciente ({$inventarioEnBodega->getCantidad()} en bodega).";
                return false;
            }

            return true;
        }

    }

    /**
     * Persiste la informacion de inventario
     *
     * @param InventarioMaterial[] $inventarios
     */
    private function updateInventarioBodegas(&$inventarios)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        foreach ($inventarios as $inventario) {
            $dm->persist($inventario);
        }
    }

    /**
     * @param MovimientoMaterial $movimientoMaterial
     * @param InventarioMaterial $inventario
     * @param MovimientoDetalle $detalle
     */
    private function handleMovimiento(MovimientoMaterial &$movimientoMaterial, InventarioMaterial &$inventario, MovimientoDetalle $detalle)
    {
        $inventarioEnBodega = $this->getInventarioEnBodega($inventario, $movimientoMaterial->getBodega());

        $movimientoMaterial->setExistente($inventarioEnBodega->getCantidad());

        if($movimientoMaterial->isAccion() == MovimientoMaterial::INVENTARIO_INGRESO) {
            $cantidadEnBodega = $inventarioEnBodega->getCantidad() + $movimientoMaterial->getCantidad();
            $cantidadEnTotal = $inventario->getCantidad() + $movimientoMaterial->getCantidad();

            // Evita que se vuela a ingresar material a esta bodega bajo saldo incial
            if($detalle->getDocumento() == MovimientoDetalle::SALDO_INICIAL) {
                $inventarioEnBodega->setSaldoInicial(true);
            }
        }
        else {
            $cantidadEnBodega = $inventarioEnBodega->getCantidad() - $movimientoMaterial->getCantidad();
            $cantidadEnTotal = $inventario->getCantidad() - $movimientoMaterial->getCantidad();
        }

        $inventario->setCantidad($cantidadEnTotal);
        $inventarioEnBodega->setCantidad($cantidadEnBodega);
    }

    /**
     * Confirma que se puede realizar el movimiento de acuerdo a los detalles enviados.
     *
     * @param MovimientoDetalle $detalle
     * @param MovimientoInventario $movimiento
     * @return MovimientoDetalle
     */
    private function processMovimientoDetalle(MovimientoDetalle $detalle, MovimientoInventario $movimiento)
    {
        $errores = [];
        $accion = $movimiento->getAccion();

        if($detalle->getDocumento() !== MovimientoDetalle::SALDO_INICIAL ) {

            $id = new \MongoId($detalle->getReferencia());
            $dm = $this->get('doctrine_mongodb')->getManager();


            switch ($detalle->getDocumento()) {
                case MovimientoDetalle::BODEGA :
                    /** @var Bodega $documento */
                    $documento = $dm->getRepository("AdministracionBundle:Bodega")->findOneBy(["id" => $id]);
                    $detalle->setBodega($documento);
                    if(!$documento->isActive()) {
                        $errores["detalle"][] = "No se puede usar la bodega (#{$documento->getCodigo()}) {$documento->getNombre()}";
                    }

                    $guia = $dm->getRepository("InventarioBundle:GuiaTransferencia")->findOneBy(["numeroGuia" =>$detalle->getAdicional()]);
                    if($accion && empty($guia)) {
                        $errores["detalle"]["adicional"][] = "Numero de Guia no es valido.";
                    }

                    if (!empty($guia) && $guia->getStatus() !== GuiaTransferencia::STATUS_WAITING) {
                        $errores["detalle"]["adicional"][] = "La Guia #{$guia->getNumeroGuia()} ya a sido completada.";
                    }

                    if(!empty($guia) && $guia->getMovimientoOrigen()->getDetalle()->getBodega()->getId() !== $movimiento->getBodega()->getId()) {
                        $errores["detalle"]["adicional"][] = "La Guia #{$guia->getNumeroGuia()} no es valida para una transaccion con esta bodega ({$movimiento->getBodega()->getCodigo()}).";
                    }

                    if(!$accion) {
                        $numeroGuia = $this->generarNumeroGuia();
                        $detalle->setAdicional($numeroGuia);
                    }
                    break;

                case MovimientoDetalle::PRODUCTOR :
                    /** @var Productor $documento */
                    $documento = $dm->getRepository("AdministracionBundle:Productor")->findOneBy(["id" => $id]);
                    $detalle->setProductor($documento);
                    if(!$documento->isActive()) {
                        $errores["detalle"][] = "No se puede usar el prodcutor (R.U.C./Cedula: {$documento->getRuc()}) {$documento->getNombre()} {$documento->getApellido()}";
                    }

                    break;

                case MovimientoDetalle::PROVEEDOR :
                    /** @var Proveedor $documento */
                    $documento = $dm->getRepository("AdministracionBundle:Proveedor")->findOneBy(["id" => $id]);
                    $detalle->setProveedor($documento);
                    if(!$documento->isActive()) {
                        $errores["detalle"][] = "No se puede usar el proveedor (R.U.C./Cedula: {$documento->getRuc()}) {$documento->getNombre()}";
                    }

                    break;
            }
        }

        if(!empty($errores)) {
            $this->throwInventarioValidationError($errores);
        }

        return $detalle;
    }

    public function handleGuias(MovimientoInventario $movimiento)
    {

        if($movimiento->getAccion() == MovimientoInventario::INVENTARIO_EGRESO && $movimiento->getDetalle()->getDocumento() == MovimientoDetalle::BODEGA) {
            $guia = new GuiaTransferencia();
            $guia->setMovimientoOrigen($movimiento);
            $guia->setNumeroGuia($movimiento->getDetalle()->getAdicional());
            $guia->setStatus(GuiaTransferencia::STATUS_WAITING);

            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($guia);
        }

        if($movimiento->getAccion() == MovimientoInventario::INVENTARIO_INGRESO && $movimiento->getDetalle()->getDocumento() == MovimientoDetalle::BODEGA) {
            $dm = $this->get('doctrine_mongodb')->getManager();
            /** @var GuiaTransferencia $guia */
            $guia = $dm->getRepository("InventarioBundle:GuiaTransferencia")->findOneBy(["numeroGuia"=> $movimiento->getDetalle()->getAdicional()]);
            $guia->setMovimientoDestino($movimiento);

            $origen = $guia->getMovimientoOrigen();
            if(count($origen->getMateriales()) !== count($movimiento->getMateriales())) $guia->setStatus(GuiaTransferencia::STATUS_ERROR);

            /** @var MovimientoMaterial $destinoMovimientoMaterial */
            foreach ($movimiento->getMateriales() as $destinoMovimientoMaterial) {
                /** @var MovimientoMaterial $origenMovimientoMaterial */
                foreach ($origen->getMateriales() as $origenMovimientoMaterial ) {
                    $destinoMaterial = $destinoMovimientoMaterial->getMaterial();
                    $origenMaterial = $origenMovimientoMaterial->getMaterial();
                    if($destinoMaterial->getId() == $origenMaterial->getId() && $destinoMovimientoMaterial->getCantidad() !== $origenMovimientoMaterial->getCantidad()) {
                        $guia->setStatus(GuiaTransferencia::STATUS_ERROR);
                    }
                }
            }

            if($guia->getStatus() !== GuiaTransferencia::STATUS_ERROR) {
                $guia->setStatus(GuiaTransferencia::STATUS_DONE);
            }

            $dm->persist($guia);
        }
    }
    public function generarNumeroGuia()
    {
        $codigo = random_int(1000, 9999)."-".random_int(1000, 9999);

        $dm = $this->get('doctrine_mongodb')->getManager();

        // Busca guia con el codigo generado.
        $guias = $dm->getRepository('InventarioBundle:GuiaTransferencia')->findBy(["numeroGuia" => $codigo]);
        if(!empty($guias)) {
            // Encontro guia, intenta de nuevo.
            return $this->generarNumeroGuia();
        }

        // No es necesario generar otro codigo, retorna el valor.
        return $codigo;
    }

    private function throwInventarioValidationError($errores) {
        $apiError = new ApiErrorMessage( 400, ApiErrorMessage::TYPE_VALIDATION_ERROR );
        $apiError->set('errors', $errores);
        throw new ApiErrorException($apiError);
    }

}