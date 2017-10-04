<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RevisionesAsesorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $this->rutaEnvio = $options['rutaEnvio'];
        $builder
          //->add('idPersona')
          ->add('tituloEntrega',TextType::Class, array ("data"=>"CORRECCIÓN DE INFORME"))

            ->add('fechaCreacion', DateTimeType::class, array(
              "data" => new \DateTime("now")
            ))

          //->add('nombreArchivo')
          ->add('rutaArchivo',FileType::class, array('data_class' => null, 'data'=>$this->rutaEnvio))
          //->add('fechaLimite')
          ->add('comentarios')
          /*
          ->add('fechaEnvio', DateTimeType::class, array(
              "data" => new \DateTime("now")
          ))
          */
          ->add('estadoRevision',ChoiceType::class,array(
                  "label" => "Prioridad",
                      "choices"=> array(
                          "Alta" =>3,
                          "Media" =>2,
                          "Baja" =>1,
                ),
                  'expanded'  => true,
                  'multiple'  => false,
              ))
          //->add('idCaso')
          ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BufeteBundle\Entity\Revisiones',
            'rutaEnvio'=> null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bufetebundle_revisiones';
    }


}
