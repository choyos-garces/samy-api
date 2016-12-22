<?php

namespace PanelBundle\Form;

use PanelBundle\Form\Type\RecursoType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewRecursoForm extends RecursoType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'is_edit' => false,
            'validation_groups' => ["Default"]
        ]);
    }
}
