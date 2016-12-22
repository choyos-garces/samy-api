<?php

namespace AdministracionBundle\Form\Proveedor\Type;

use AdministracionBundle\Document\Proveedor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("nombre")
            ->add("ruc")
            ->add("correo")
            ->add("telefono")
            ->add("active", CheckboxType::class, [
                "disabled" => !$options["is_edit"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proveedor::class,
            'csrf_protection' => false,
            'is_edit' => false
        ]);
    }
}
