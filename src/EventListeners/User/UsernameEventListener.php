<?php

namespace App\EventListeners\User;

use App\Services\User\UserService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;

class UsernameEventListener implements EventSubscriberInterface
{

    public function __construct(private UserService $userService, private Security $security) {}

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    public function onSubmit(SubmitEvent $event)
    {
        $form = $event->getForm();
        $formUser = $form->getData();

        if (!$formUser) {
            return;
        }

        $existingUser = $this->userService->findUserByUsername($formUser->getUsername());
        $currentUserId = $this->security->getUser()->getId();

        // $existingUser->getId() !== $currentUserId => Pour le cas ou l'utilisateur soumet le même pseudo
        if ($existingUser && $existingUser['id'] !== $currentUserId) {
            $form->get('username')->addError(new FormError('Ce pseudonyme est déjà utilisé.'));
        }
    }
}
