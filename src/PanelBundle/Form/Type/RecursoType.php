<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/19/2016
 * Time: 6:06 PM
 */

namespace PanelBundle\Form\Type;


use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use PanelBundle\Document\Recurso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("nombre")
            ->add("aplicacion", DocumentType::class, [
                "class" => 'PanelBundle:Aplicacion',
                "choice_attr" => 'id',
                "disabled" => $options["is_edit"]
            ])
            ->add("seccion", DocumentType::class, [
                "class" => 'PanelBundle:Seccion',
                "choice_attr" => 'id',
                "disabled" => $options["is_edit"]
            ])
            ->add("detalle", TextareaType::class)
            ->add("active", CheckboxType::class, [
                "disabled" => !$options["is_edit"]
            ])
            ->add('categorias', CollectionType::class, [
                "entry_type" => CategoriaType::class,
                "allow_add" => true,
                "allow_delete" => false,
                "disabled" => !$options["is_edit"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recurso::class,
            'csrf_protection' => false
        ]);
    }
}