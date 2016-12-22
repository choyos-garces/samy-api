<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/26/2016
 * Time: 6:34 PM
 */

namespace AppBundle\DataFixtures\MongoDB;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PanelBundle\Document\Aplicacion;
use PanelBundle\Document\Categoria;
use PanelBundle\Document\Recurso;
use PanelBundle\Document\Seccion;

class LoadResourcesData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $inventario = new Aplicacion();
        $inventario->setId("_INVENTARIO");
        $inventario->setNombre("Inventario");
        $manager->persist($inventario);

        $tipoMaterial = new Seccion();
        $tipoMaterial->setId("_TIPO_MATERIAL");
        $tipoMaterial->setNombre("Tipo Material");
        $tipoMaterial->setAplicacion($inventario);
        $manager->persist($tipoMaterial);

        $movimientos = new Seccion();
        $movimientos->setId("_MOVIMIENTOS");
        $movimientos->setNombre("Movimientos");
        $movimientos->setAplicacion($inventario);
        $manager->persist($movimientos);

        $inventario->addSeccion($tipoMaterial);
        $inventario->addSeccion($movimientos);

        $ingresoInventario = new Recurso();
        $ingresoInventario->setAplicacion(   $inventario);
        $ingresoInventario->setSeccion($movimientos);
        $ingresoInventario->setNombre("Ingreso de Inventario");
        $ingresoInventario->setActive(true);
        $ingresoInventario->setDetalle("1");
        $manager->persist($ingresoInventario);

        $egresoInventario = new Recurso();
        $egresoInventario->setAplicacion($inventario);
        $egresoInventario->setSeccion($movimientos);
        $egresoInventario->setNombre("Egreso de Inventario");
        $egresoInventario->setActive(true);
        $egresoInventario->setDetalle("0");
        $manager->persist($egresoInventario);

        $carton = new Recurso();
        $carton->setAplicacion($inventario);
        $carton->setSeccion($tipoMaterial);
        $carton->setNombre("Carton y Cartulinas");
        $carton->setActive(true);
        $manager->persist($carton);
        
        $manager->flush();
    }
}