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

class PersonasEstudianteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

      $this->carneEnvio = $options['carneEnvio'];
      $this->nomEnvio = $options['nombreEnvio'];
      $this->telEnvio = $options['telefonoEnvio'];
      $this->dirEnvio = $options['direccionEnvio'];
      $this->corEnvio = $options['correoEnvio'];
      $this->passEnvio = $options['passEnvio'];

      $builder

      //form DATOS PERSONALES
        
        ->add('nombrePersona',TextType::Class, array ("data"=>$this->nomEnvio))
        ->add('telefonoPersona',TextType::Class, array ("data"=>$this->telEnvio))
        ->add('tel2Persona')
        ->add('dpiPersona')
        ->add('direccionPersona',TextType::Class, array ("data"=>$this->dirEnvio))
        ->add('emailPersona',TextType::Class, array ("data"=>$this->corEnvio))
        ->add('usuarioPersona',TextType::Class, array ("label"=>"Usuario"))
        ->add('passPersona',TextType::Class, array ("data"=>"$this->passEnvio"))
        ->add('estadoPersona')
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
                'label'=>' ',
                'carneEnvio' =>$this->carneEnvio,
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
            'nombreEnvio'=> null,
            'telefonoEnvio'=> null,
            'direccionEnvio'=> null,
            'correoEnvio'=> null,
            'passEnvio' => null,

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
