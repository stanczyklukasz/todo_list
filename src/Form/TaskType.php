<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextType::class, [

            ])
            ->add('priority', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 3
                ]
            ])
            ->add('btn_submit', SubmitType::class, [
                'label' => "Zapisz"
            ]);
    }
}