<?php


namespace App\Form\User;

use App\EventListeners\User\PasswordEventListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class UpdatePasswordFormType extends AbstractType
{

    public function __construct(private PasswordEventListener $passwordEventListener) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', PasswordType::class, [
            'required' => true,
            'label' => 'Nouveau mot de passe',
            'constraints' => [
                new NotBlank(['message' => 'Veuillez renseigner un mot de passe']),
                new NotNull(['message' => 'Veuillez renseigner un mot de passe valide']),
                new Length([
                    'min' => 7,
                    'minMessage' => 'Le mot de passe doit contenir au minimum {{ limit }} caractères',
                    'max' => 17,
                    'maxMessage' => 'Le mot de passe doit contenir au maximum {{ limit }} caractères'
                ]),
                new Regex([
                    'pattern' => '/^[a-zA-Z0-9]+$/',
                    'message' => 'Le mot de passe ne peut contenir que des lettres et des chiffres'
                ])
            ]
        ])
            ->add('confirm_password', PasswordType::class, [
                'required' => true,
                'label' => 'Confirmez le mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez confirmer votre mot de passe'])
                ]
            ]);

        $builder->addEventSubscriber($this->passwordEventListener);
    }
}
