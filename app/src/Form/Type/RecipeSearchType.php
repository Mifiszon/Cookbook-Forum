<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 */
class RecipeSearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredients', TextareaType::class, [
                'label' => 'label.ingredients',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter ingredients separated by commas',
                ],
            ]);
    }
}
