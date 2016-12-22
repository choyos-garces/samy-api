<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/27/2016
 * Time: 11:00 AM
 */

namespace AdministracionBundle\Form\Material\Type;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use AdministracionBundle\Document\Material;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("tipoMaterial", DocumentType::class, [
                "class" => 'PanelBundle:Recurso',
                "choice_attr" => 'id',
                "disabled" => $options["is_edit"]
            ])
            ->add("categoria", DocumentType::class, [
                "class" => 'PanelBundle:Categoria',
                "choice_attr" => 'id',
                "disabled" => $options["is_edit"]
            ])
            ->add("codigo")
            ->add("nombre")
            ->add("descripcion")
            ->add("active", CheckboxType::class, [
                "disabled" => !$options["is_edit"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Material::class,
            'csrf_protection' => false
        ]);
    }
}