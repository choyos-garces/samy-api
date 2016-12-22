<?php

namespace AppBundle\Form\Seccion\Type;

use PanelBundle\Document\Seccion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("id", TextType::class, [
                "disabled" => $options["is_edit"]
            ])
            ->add("nombre")
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'is_edit' => false,
            'data_class' => Seccion::class
        ]);
    }
}
