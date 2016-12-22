<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/24/2016
 * Time: 1:27 PM
 */

namespace PanelBundle\Form;

use PanelBundle\Form\Type\RecursoType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditRecursoForm extends RecursoType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add("id", TextType::class)
            ->add("created", DateTimeType::class, array(
                'widget' => 'single_text'
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            "is_edit" => true,
            "validation_groups" => ["Default", "Edit"]
        ]);
    }
}