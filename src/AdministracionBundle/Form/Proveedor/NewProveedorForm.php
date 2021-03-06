<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/2/2016
 * Time: 4:20 PM
 */

namespace AdministracionBundle\Form\Proveedor;

use AdministracionBundle\Form\Proveedor\Type\ProveedorType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewProveedorForm extends ProveedorType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'validation_groups' => ["Default"]
        ]);
    }
}