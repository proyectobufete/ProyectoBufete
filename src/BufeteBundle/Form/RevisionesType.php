<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class RevisionesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

      $this->fechLimEnvio = $options['fechLimEnvio'];
        $builder
          //->add('idPersona')

          ->add('tituloEntrega')

          ->add('fechaLimite', DateTimeType::class, array(
            "data" => new \DateTime($this->fechLimEnvio),
            'widget' => 'single_text',
          ))

          ->add('fechaCreacion', HiddenType::class, array(
                'data' => '2011/02/05',))
          //->add('nombreArchivo')
          //->add('rutaArchivo',FileType::class, array('data_class' => null, 'data'=>$this->rutaEnvio))

          //->add('comentarios')
          //->add('fechaEnvio')

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
          //->add(‘idRevisado’)
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
            'fechLimEnvio' => null,
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
