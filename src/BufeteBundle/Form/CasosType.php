<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CasosType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('noCaso')->add('fechaCaso')->add('pruebasCaso')->add('asignatarioCaso')->add('estadoCaso')->add('nombreDemandado')->add('dirDemandado')->add('dirnotificacionDemandado')->add('telefonoDemandado')->add('otroDemandado')->add('observaciones')->add('idDemandante')->add('idEstudiante')->add('idTribunal')->add('idPersona')->add('idTipo')->add('idTipoasunto');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BufeteBundle\Entity\Casos'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bufetebundle_casos';
    }


}
