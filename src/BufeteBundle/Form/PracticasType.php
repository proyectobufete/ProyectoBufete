<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PracticasType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('inicioPractica', DateType::class,  array(
          "data" => new \DateTime("now"),
          'widget' => 'single_text'
        ))
        ->add('fechaFin', DateType::class, array(
          'widget' => 'single_text'
        ))
        ->add('estadoPractica', ChoiceType::class,array(
          "label" => "Estado del caso: ",
          "choices"=> array(
            "Activo" => 1,
            "Inactivo" => 0,
          )
        ))
        ->add('observaciones')
        ->add('idEstudiante');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BufeteBundle\Entity\Practicas'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bufetebundle_practicas';
    }


}
