<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/2/2016
 * Time: 4:20 PM
 */

namespace AdministracionBundle\Form\Productor;

use AdministracionBundle\Form\Productor\Type\ProductorType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProductorType extends ProductorType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'is_edit' => true,
            'validation_groups' => ["Default", "Edit"]
        ]);
    }
}
