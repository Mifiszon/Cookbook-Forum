<?php
/**
 * User Type.
 */

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserType.
 */
class UserType extends AbstractType
{
    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder FormBuilderInterface
     * @param array                $options Options array
     *
     * @return void Void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('nickname', TextType::class, [
                'label' => 'label.nickname',
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ],
            ]);

        if (!$options['is_edit'] || $options['change_password']) {
            $builder->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => ['label' => 'label.password'],
                'second_options' => ['label' => 'label.repeatPassword'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                    ]),
                ],
            ]);
        }

        if (!$options['is_edit']) {
            $builder->add('password', HiddenType::class, [
                'mapped' => false,
            ]);
        }
    }

    /**
     * Config Options.
     *
     * @param OptionsResolver $resolver Resolver
     *
     * @return void Void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
            'change_password' => false,
            'validation_groups' => ['registration'],
        ]);
    }
}
