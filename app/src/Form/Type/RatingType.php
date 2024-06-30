<?php
/**
 * Rating Type.
 */

namespace App\Form\Type;

use App\Entity\Rating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RatingType
 */
class RatingType extends AbstractType
{
    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder FormBuilderInterface.
     * @param array                $options Options array.
     *
     * @return void Void.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'label' => 'label.rate',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
            ]);
    }

    /**
     * Configure Options.
     *
     * @param OptionsResolver $resolver Resolver.
     *
     * @return void Void.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}
