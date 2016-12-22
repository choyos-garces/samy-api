<?php

namespace InventarioBundle\Form;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use InventarioBundle\Form\Movimientos\Type\MovimientoDetalleType;
use InventarioBundle\Form\Movimientos\Type\MovimientoInventarioType;
use InventarioBundle\Form\Movimientos\Type\MovimientoMaterialType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewMovimientosForm extends MovimientoInventarioType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            "is_edit" => false,
            "csrf_protection" => false
        ]);
    }
}
