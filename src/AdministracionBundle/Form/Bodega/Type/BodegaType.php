<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/27/2016
 * Time: 11:00 AM
 */

namespace AdministracionBundle\Form\Bodega\Type;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use AdministracionBundle\Document\Bodega;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BodegaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("codigo", TextType::class, [
                "disabled" => $options["is_edit"]
            ])
            ->add("nombre")
            ->add("encargado", DocumentType::class, [
                "class" => 'AdministracionBundle:Personal',
                "choice_attr" => 'id',
            ])
            ->add("descripcion")
            ->add("active", CheckboxType::class, [
                "disabled" => !$options["is_edit"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bodega::class,
            'csrf_protection' => false
        ]);
    }
}