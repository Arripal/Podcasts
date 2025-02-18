<?php

namespace App\Form\User;

use App\Entity\User;
use App\EventListeners\User\UsernameEventListener;
use App\Services\User\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class UpdateUsernameFormType extends AbstractType
{
    public function __construct(private UserService $userService, private UsernameEventListener $usernameEventListener) {}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, ['label' => 'Nouveau pseudonyme', 'constraints' => [
            new NotBlank(['message' => 'Veuillez renseigner un pseudonyme']),
            new NotNull(['message' => 'Veuillez renseigner un pseudonyme']),
            new Length([
                'min' => 3,
                'minMessage' => 'Le pseudonyme doit contenir au minimum {{ limit }} caractères',
                'max' => 15,
                'maxMessage' => 'Le pseudonyme doit contenir au maximum {{ limit }} caractères'
            ])
        ]]);

        $builder->addEventSubscriber($this->usernameEventListener);
    }
}
