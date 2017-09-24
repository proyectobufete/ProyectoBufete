<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LaboralesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fechaInicio')->add('fechaFin')->add('salario')->add('vaciones')->add('indemnizacion')->add('diaseptimos')->add('bonoanual')->add('horasextra')->add('bonoincentivo')->add('diasasueto')->add('aguinaldo')->add('reajustesalarial')->add('salariosretenidos')->add('otros')->add('otros2')->add('idCaso')->add('idTrabajo');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BufeteBundle\Entity\Laborales'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bufetebundle_laborales';
    }


}
