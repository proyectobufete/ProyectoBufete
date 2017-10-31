<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class editarestudianteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

      $this->carneEnvio = $options['carneEnvio'];

      $builder

        ->add('nombrePersona')
        ->add('telefonoPersona')
        ->add('tel2Persona')
        ->add('dpiPersona')
        ->add('direccionPersona')
        ->add('emailPersona')
        ->add('usuarioPersona',TextType::Class, array ("label"=>"Usuario"))
        //->add('passPersona')
        ->add('estadoPersona',ChoiceType::class,array(
                "label" => "Estado",
                    "choices"=> array(
                        "ACTIVO " =>1,
                        "INACTIVO" =>0,
              ),
                'expanded'  => true,
                'multiple'  => false,
              ))
            ->add('role', HiddenType::class, array(
              'data' => 'ROLE_ESTUDIANTE',))

            //->add('idBufete')


            ->add('estudiantes', 'BufeteBundle\Form\EstudiantesType', array(
              'carneEnvio' =>$this->carneEnvio,
                'label' => ' ',
            ))

            ->add('save', SubmitType::class, array(
              'attr' => array('class' => 'save')
              ))

          ;
    }



    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(

            'data_class' => 'BufeteBundle\Entity\Personas',
            'carneEnvio'=> null,

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bufetebundle_personas';
    }


}
