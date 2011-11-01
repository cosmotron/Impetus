<?php

namespace Impetus\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StudentCourseType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('course', 'entity', array('class' => 'ImpetusAppBundle:Course',
                                            'query_builder' => function ($repository) { return $repository->createQueryBuilder('c')->orderBy('c.name', 'ASC'); },
                                            )
                  )
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Impetus\AppBundle\Entity\StudentCourse');
    }

    public function getName()
    {
        return 'impetus_appbundle_studentcoursetype';
    }
}
