<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RevisionesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $this->rutaEnvio = $options['rutaEnvio'];
        $builder
          ->add('idPersona')
          ->add('tituloEntrega')
          ->add('fechaCreacion', DateType::class, array(
              "data" => new \DateTime("now"),
              'widget' => 'single_text'
          ))
          ->add('nombreArchivo')
          ->add('rutaArchivo',FileType::class, array('data_class' => null, 'data'=>$this->rutaEnvio))
          ->add('fechaLimite')
          ->add('comentarios')
          ->add('fechaEnvio')
          ->add('estadoRevision')
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
