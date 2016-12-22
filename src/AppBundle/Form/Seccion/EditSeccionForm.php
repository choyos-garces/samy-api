<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/9/2016
 * Time: 6:54 PM
 */

namespace AppBundle\Form\Seccion;

use AppBundle\Form\Seccion\Type\SeccionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditSeccionForm extends SeccionType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'is_edit' => true
        ]);
    }
}