<?php

namespace InventarioBundle\Form\Movimientos\Type;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use InventarioBundle\Document\MovimientoMaterial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovimientoMaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("material", DocumentType::class, [
                "class" => 'AdministracionBundle\Document\Material',
                "choice_attr" => 'id',
            ])
            ->add("cantidad")
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => MovimientoMaterial::class
        ]);
    }
}
