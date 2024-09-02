<?php
/**
 * RevokeType.
 */

namespace App\Form\Type\Privileges;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RevokeType.
 */
class RevokeType extends AbstractType
{
    /**
     * Form Builer.
     *
     * @param FormBuilderInterface $builder builder
     * @param array                $options options array
     *
     * @return void void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Revoke Admin Privileges',
                'attr' => ['class' => 'btn btn-danger'],
            ]);
    }
}
