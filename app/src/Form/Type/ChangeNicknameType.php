<?php
/**
 * Change Nickname Type.
 */

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChangeNicknameType.
 */
class ChangeNicknameType extends AbstractType
{
    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder FormBuilderInterface
     * @param array                $options options array
     *
     * @return void void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname', TextType::class, [
                'label' => 'New Nickname',
                'required' => true,
            ]);
    }

    /**
     * Config Options.
     *
     * @param OptionsResolver $resolver resolver
     *
     * @return void void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
