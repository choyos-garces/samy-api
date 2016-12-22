<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/27/2016
 * Time: 11:08 AM
 */

namespace AdministracionBundle\Form\Bodega;

use AdministracionBundle\Form\Bodega\Type\BodegaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditBodegaForm extends BodegaType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            "is_edit" => true,
            "validation_groups" => ["Default", "Edit"]
        ]);
    }
}