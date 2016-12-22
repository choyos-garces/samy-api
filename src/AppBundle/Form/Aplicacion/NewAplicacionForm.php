<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/9/2016
 * Time: 6:55 PM
 */

namespace AppBundle\Form\Aplicacion;

use AppBundle\Form\Aplicacion\Type\AplicacionType;
use AppBundle\Form\Seccion\NewSeccionForm;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewAplicacionForm extends AplicacionType
{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder
            ->add("secciones", CollectionType::class, [
                'entry_type' => NewSeccionForm::class,
                'allow_add' => true
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }
}