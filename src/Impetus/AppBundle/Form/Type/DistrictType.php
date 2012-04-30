<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class DistrictType extends AbstractType {
    public function buildForm(FormBuilder $builder, array $options) {
        //$builder->add('name');
        $builder->add('name', 'entity', array('empty_value'   => 'Choose a district',
                                              'class'         => 'ImpetusAppBundle:District',
                                              'query_builder' => function(EntityRepository $er) {
                                                  return $er->createQueryBuilder('d')
                                                      ->orderBy('d.name', 'ASC');
                                              }));
    }

    public function getDefaultOptions(array $options) {
        return array('data_class' => 'Impetus\AppBundle\Entity\District');
    }

    public function getName(){
        return 'district';
    }
}