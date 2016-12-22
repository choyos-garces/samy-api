<?php

namespace AdministracionBundle\Form\Productor\Type;

use AdministracionBundle\Document\Productor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("nombre")
            ->add("apellido")
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
            'data_class' => Productor::class,
            'csrf_protection' => false,
            'is_edit' => false
        ]);
    }
}
