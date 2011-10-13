<?php

namespace Impetus\AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class RoleType extends AbstractType {
    public function buildForm(FormBuilder $builder, array $options) {
        $builder->add('name', 'entity', array('empty_value'   => 'Choose a role',
                                              'class'         => 'ImpetusAppBundle:Role',
                                              'query_builder' => function(EntityRepository $er) {
                                                  return $er->createQueryBuilder('r')
                                                      ->orderBy('r.name', 'ASC');
                                              }));
    }

    public function getDefaultOptions(array $options) {
        return array('data_class' => 'Impetus\AppBundle\Entity\Role');
    }

    public function getName(){
        return 'role';
    }
}