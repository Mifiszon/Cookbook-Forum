<?php
/**
 * PromoteType.
 */

namespace App\Form\Type\Privileges;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PromoteType.
 */
class PromoteType extends AbstractType
{
    /**
     * Form Builder.
     *
     * @param FormBuilderInterface $builder builder
     * @param array                $options options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Promote to Admin',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
