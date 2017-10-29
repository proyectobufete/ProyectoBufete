<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AsignacionclinicaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->bufete = $options['bufete'];
        $builder->add('notaClinica')
        ->add('observacionesClinica')
        ->add('estadoAsignacionest')
        ->add('idEstudiante')
        ->add('idClinica', EntityType::class,array(
          "class" => "BufeteBundle:Clinicas",
          "label" => "Clinica: ",
          "query_builder" => function (EntityRepository $er){
            return $er->createQueryBuilder('c')
            ->innerJoin('BufeteBundle:Personas', 'p', Join::WITH, 'p.idPersona = c.idPersona')
            ->where('p.idBufete = :bufete')
            ->andWhere('c.estadoClinica = 1')
            ->setParameter('bufete', $this->bufete);
          },
          'placeholder' => 'Ninguno seleccionado',
          'required'   => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BufeteBundle\Entity\Asignacionclinica',
            'bufete' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bufetebundle_asignacionclinica';
    }


}
