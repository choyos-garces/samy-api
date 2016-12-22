<?php

namespace InventarioBundle\Form\Movimientos\Type;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use InventarioBundle\Document\MovimientoInventario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovimientoInventarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("accion")
            ->add("bodega", DocumentType::class, [
                'class' => 'AdministracionBundle\Document\Bodega'
            ])
            ->add("detalle", MovimientoDetalleType::class)
            ->add("materiales", CollectionType::class, [
                "entry_type" => MovimientoMaterialType::class,
                "allow_add" => true,
                "disabled" => $options["is_edit"]
            ])
            ->add("observaciones")
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MovimientoInventario::class
        ]);
    }
}
