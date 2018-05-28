<?php

namespace App\Form\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EmployeeType
 * @package App\Form\FormType
 */
class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('name', TextType::class)
            ->add('phone', TelType::class)
            ->add('birthday', DateTimeType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd 00:00:00'])
            ->add('gender', TextType::class)
            ->add('salary', MoneyType::class)
            ->add('notes', TextType::class)
            ->getForm();
    }
}