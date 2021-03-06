<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/2/2016
 * Time: 4:20 PM
 */

namespace AdministracionBundle\Form\Personal;

use AdministracionBundle\Form\Personal\Type\PersonalType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPersonalForm extends PersonalType
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
