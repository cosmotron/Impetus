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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class CoreUserType extends AbstractType {
    public function buildForm(FormBuilder $builder, array $options) {
        $this->coreFields($builder);
    }

    public function getDefaultOptions(array $options) {
        return array('data_class' => 'Impetus\AppBundle\Entity\User');
    }

    public function getName() {
        return 'coreuser';
    }

    protected function coreFields(FormBuilder $builder) {
        $builder
            ->add('password', 'password')
            ->add('firstName', null,
                  array('label' => 'First Name',
                        )
                  )
            ->add('lastName', null,
                  array('label' => 'Last Name',
                        )
                  )
            ->add('email')
            ->add('birthday', 'birthday',
                  array('years' => range(date('Y'), (date('Y') - 100)),
                        'required' => false,
                        )
                  )
            ->add('ethnicity', 'choice',
                  array('choices' => array('American Indian or Alaskan Native' => 'American Indian or Alaskan Native',
                                           'Asian' => 'Asian',
                                           'Bi-racial' => 'Bi-racial',
                                           'Black or African American' => 'Black or African American',
                                           'Hispanic' => 'Hispanic',
                                           'Pacific Islander' => 'Pacific Islander',
                                           'White' => 'White',
                                           'Other' => 'Other'),
                        'required'  => false,
                        )
                  )
            ->add('gender', 'choice',
                  array('choices' => array('Male' => 'Male',
                                           'Female' => 'Female'
                                           ),
                        'required'  => false,
                        )
                  )
            ->add('userRoles', 'entity',
                  array('empty_value' => 'Choose a role',
                        'class'       => 'ImpetusAppBundle:Role',
                        'label'       => 'User Role',
                        'multiple'    => true,
                        )
                  )
            ->add('graduated', 'entity',
                  array('class' => 'ImpetusAppBundle:Year',
                        'query_builder' => function ($repository) { return $repository->createQueryBuilder('y')->orderBy('y.year', 'DESC'); },
                        'required' => false,
                        )
                  )
            ->add('diploma', 'choice',
                  array('choices' => array('Advanced Regents' => 'Advanced Regents',
                                           'Regents' => 'Regents'),
                        'required' => false
                        )
                  )
            ->add('college', 'text',
                  array('required' => false
                        )
                  )
            ->add('major', 'choice',
                  array('choices' => array('Architechture' => 'Architecture',
                                           'Biological and Biomedical Sciences' => 'Biological and Biomedical Sciences',
                                           'Business, Management, and Marketing' => 'Business, Management, and Marketing',
                                           'Communications' => 'Communications',
                                           'Computer and Information Science' => 'Computer and Information Science',
                                           'Education' => 'Education',
                                           'Engineering' => 'Engineering',
                                           'Humanities' => 'Humanities',
                                           'Mathematics and Statistics' => 'Mathematics and Statistics',
                                           'Physical Science' => 'Physical Science',
                                           'Psychology' => 'Psychology',
                                           'Other' => 'Other',
                                           ),
                        'required'  => false,
                        )
                  )
            ;
    }
}