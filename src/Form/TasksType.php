<?php

namespace App\Form;

use App\Entity\Actions;
use App\Entity\Tasks;
use App\Transformer\ActionsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TasksType extends AbstractType
{
    private $actionsTransformer;

    public function __construct(ActionsTransformer $transformer)
    {
        $this->actionsTransformer = $transformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('archived')
            // ->add('actions', FormType::class, [
            //     'data_class' => Actions::class
            // ])
            // ->add(
            //     $builder->create('actions', FormType::class, ['by_reference' => false])
            //         ->add('status', CheckboxType::class, [
            //             'label' => ' ',
            //             'required' => false,
            //             'data_class' => Actions::class
            //         ])
            //         ->add('description', TextType::class, [
            //             'label' => ' ',
            //             'required' => false
            //         ])
            //         ->get('status')->addModelTransformer($this->actionsTransformer)
            // )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class
        ]);
    }
}
