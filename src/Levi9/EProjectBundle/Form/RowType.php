<?php

namespace Levi9\EProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RowType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Levi9\EProjectBundle\Entity\Row',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'type',
                'text',
                array('label' => 'add.form.type')
            )
            ->add(
                'count',
                'integer',
                array(
                    'label' => 'add.form.count',
                    'attr' => array('min' => 1),
                )
            )
            ->add(
                'name',
                'text',
                array(
                    'label' => 'add.form.name',
                    'required' => false,
                )
            )
            ->add(
                'add',
                'submit',
                array(
                    'label' => 'add.form.submit',
                )
            );
    }

    public function getName()
    {
        return 'row';
    }
}
