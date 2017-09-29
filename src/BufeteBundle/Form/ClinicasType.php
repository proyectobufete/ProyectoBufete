<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class ClinicasType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->idciudad = $options['idciudad'];
        $builder->add('nombreClinica')
        ->add('fechaAsignacion', DateType::class,  array(
          "data" => new \DateTime("now"),
          'widget' => 'single_text'
        ))
        ->add('fechaFin', DateType::class, array(
          'widget' => 'single_text'
        ))
        ->add('observaciones')
        ->add('estadoClinica', ChoiceType::class,array(
          "label" => "Estado del caso: ",
          "choices"=> array(
            "Activo" => 1,
            "Inactivo" => 0,
          )
        ))
        ->add('idTipo')
        ->add('idPersona', EntityType::class,array(
          "class" => "BufeteBundle:Personas",
          "label" => "Asesor: ",
          "query_builder" => function (EntityRepository $er){
            return $er->createQueryBuilder('persona')
            ->innerJoin('BufeteBundle:Bufetes', 'b', Join::WITH, 'persona.idBufete = b.idBufete')
            ->where('persona.role = :rol')
            ->andWhere('b.idCiudad = :ciudad')
            ->setParameter('rol', 'ROLE_ASESOR')
            ->setParameter('ciudad', $this->idciudad);
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
            'data_class' => 'BufeteBundle\Entity\Clinicas',
            'idciudad' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bufetebundle_clinicas';
    }


}
