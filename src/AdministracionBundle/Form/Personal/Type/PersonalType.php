<?php

namespace AdministracionBundle\Form\Personal\Type;

use AdministracionBundle\Document\Personal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("nombre")
            ->add("apellido")
            ->add("cedula")
            ->add("correo")
            ->add("codigo", TextType::class, [
                "disabled" => $options["is_edit"]
            ])
            ->add("telefono")
            ->add("celular")
            ->add("active", CheckboxType::class, [
                "disabled" => !$options["is_edit"]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personal::class,
            'csrf_protection' => false,
            'is_edit' => false
        ]);
    }
}
