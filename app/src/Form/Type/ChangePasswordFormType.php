<?php
/**
 * Change Password Form Type.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChangePasswordFormType.
 */
class ChangePasswordFormType extends AbstractType
{
    /**
     * Form Builder.
     *
     * @param FormBuilderInterface $builder formBuilderInterface
     * @param array                $options options array
     *
     * @return void void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'label.CurrentPassword',
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'label.NewPassword',
            ]);
    }

    /**
     * Configure Options.
     *
     * @param OptionsResolver $resolver resolver
     *
     * @return void void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
