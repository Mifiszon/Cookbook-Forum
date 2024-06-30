<?php
/**
 * Recipe Search Type.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RecipeSearchType.
 */
class RecipeSearchType extends AbstractType
{
    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder FormBuilderInterface
     * @param array                $options Array.
     *
     * @return void Void.
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
