<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PersonasAsesorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $this->passEnvio = $options['passEnvio'];
        $builder
        ->add('idPersona',HiddenType::class, array(

              'required' => false,
            ))
            ->add('nombrePersona')
            ->add('telefonoPersona')
            ->add('tel2Persona')
            ->add('dpiPersona')
            ->add('direccionPersona')
            ->add('emailPersona')
            ->add('usuarioPersona',TextType::Class, array ("label"=>"Usuario"))
            ->add('passPersona',TextType::Class, array (
                   "data"=>"$this->passEnvio"))
            ->add('estadoPersona',ChoiceType::class,array(
                "label" => "Estado",
                    "choices"=> array(
                        "ACTIVO" =>1,
                        "INACTIVO" =>2,
              ),
                'expanded'  => true,
                'multiple'  => false,
            ))
            //->add('role')
            ->add('role', HiddenType::class, array(
              'data' => 'ROLE_ASESOR',))
            ->add('foto',FileType::class, array(
                  'label' => 'Foto de Perfil',
                  'attr' =>array('class'=>'form-control'),
                  'data_class' => null,
                  'required' => false,
                ))


          ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BufeteBundle\Entity\Personas',
            'passEnvio' => null,
        ));
    }

}
