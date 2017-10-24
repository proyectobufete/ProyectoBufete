<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class RevEstEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $this->rutaEnvio = $options['rutaEnvio'];
        $builder
          ->add('tituloEntrega')
          ->add('rutaArchivo',FileType::class, array(
              'label' => 'Informe PDF',
              'attr' =>array('class'=>'form-control'),
              'data_class' => null,
              'required' => false,
            ))
          ->add('comentarios')
        
          ->add('fechaEnvio', HiddenType::class, array(
                'data' => '2011/02/05',))
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
