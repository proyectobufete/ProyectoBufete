<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use BufeteBundle\Form\AvisoNotificacionType;
use BufeteBundle\Entity\AvisoNotificacion;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class AvisoNotificacionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('descripcion', EntityType::class,array(
              "class" => "BufeteBundle:Casos",
              "label" => "Casos: ",
              "query_builder" => function (EntityRepository $er){
                return $er->createQueryBuilder('caso')
                ;
              },
              'placeholder' => 'Ninguno seleccionado',
              'required'   => true,
            ))

            ->add('pdf', FileType::class,array(
              "mapped"=>false,
              "attr"=>array(
              "title" => "Cargar PDF"
            )))


            ->add('aceptar', SubmitType::class,array("attr"=>array(
              "class" => "form-submit btn btn-success"
            )))

            ;


    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BufeteBundle\Entity\AvisoNotificacion'
        ));
    }
}
