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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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


            ->add('fechaCreacion', HiddenType::class, array(
                  'data' => '2011/02/05',))

          //->add('nombreArchivo')
          ->add('rutaArchivo',FileType::class, array('data_class' => null, 'data'=>$this->rutaEnvio))
          //->add('fechaLimite')
          ->add('comentarios')
          /*
          ->add('fechaEnvio', DateTimeType::class, array(
              "data" => new \DateTime("now")
          ))
          */
          ->add('estadoRevision', HiddenType::class, array(
                'data' => 3,))
          //->add(‘idRevisado’)
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
