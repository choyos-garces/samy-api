<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/27/2016
 * Time: 11:08 AM
 */

namespace AdministracionBundle\Form\Material;

use AdministracionBundle\Form\Material\Type\MaterialType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewMaterialForm extends MaterialType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            "is_edit" => false,
            "validation_groups" => ["Default"]
        ]);
    }
}