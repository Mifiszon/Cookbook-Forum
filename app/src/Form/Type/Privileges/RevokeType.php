<?php

namespace App\Form\Type\Privileges;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RevokeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Revoke Admin Privileges',
                'attr' => ['class' => 'btn btn-danger'],
            ]);
    }
}
