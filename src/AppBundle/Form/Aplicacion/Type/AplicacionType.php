<?php

namespace AppBundle\Form\Aplicacion\Type;

use AppBundle\Form\Seccion\Type\SeccionType;
use PanelBundle\Document\Aplicacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AplicacionType extends AbstractType
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
            'data_class' => Aplicacion::class
        ]);
    }
}
