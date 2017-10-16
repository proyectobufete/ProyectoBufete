<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RevisionesEstudiantesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $this->rutaEnvio = $options['rutaEnvio'];
        $builder
          //->add('idPersona')
          ->add('tituloEntrega')
          /*
            ->add('fechaCreacion', DateTimeType::class, array(
              "data" => new \DateTime("now")
            ))
          */
          //->add('nombreArchivo')
          ->add('rutaArchivo',FileType::class, array('data_class' => null, 'data'=>$this->rutaEnvio))
          //->add('fechaLimite')
          ->add('comentarios')

          ->add('fechaEnvio')

          //->add('estadoRevision')
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
