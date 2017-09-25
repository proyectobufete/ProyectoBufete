<?php

namespace BufeteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CasosType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->idciudad = $options['idciudad'];
        $builder
        ->add('noCaso')
        ->add('fechaCaso', DateType::class, array(
          "data" => new \DateTime("now"),
          'widget' => 'single_text'
        ))
        ->add('pruebasCaso')
        //->add('asignatarioCaso')
        ->add('estadoCaso', ChoiceType::class,array(
          "label" => "Estado del caso: ",
          "choices"=> array(
            "No asignado" => 0,
            "En tramite" => 1,
            "Descargado" => 2,
            "Fenecido" => 3,
          )
        ))
        ->add('nombreDemandado')
        ->add('dirDemandado')
        ->add('dirnotificacionDemandado')
        ->add('telefonoDemandado')
        ->add('otroDemandado')
        ->add('observaciones')
        ->add('idDemandante', EntityType::class,array(
          "class" => "BufeteBundle:Demandantes",
          "label" => "Demandante: ",
          "query_builder" => function (EntityRepository $er){
            return $er->createQueryBuilder('demandante')
            ->where('demandante.idCiudad = :ciudad')
            ->setParameter('ciudad', $this->idciudad);
          },
          'placeholder' => 'Ninguno seleccionado',
          'required'   => true,
        ))
        ->add('idEstudiante')
        ->add('idTribunal')
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
        ))
        //->add('idTipo')
        ->add('idTipoasunto')
        ->add('laborales', 'BufeteBundle\Form\LaboralesType', array(
            'label' => 'Laborales',
        ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BufeteBundle\Entity\Casos',
            'idciudad' => null,
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
