<?php

namespace App\EventListeners\User;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;

class PasswordEventListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    public function onSubmit(SubmitEvent $event)
    {
        $form = $event->getForm();
        $plainPassword = $form->get('password')->getData();
        $confirmPassword = $form->get('confirm_password')->getData();

        if ($plainPassword !== $confirmPassword) {
            $form->get('confirm_password')->addError(new FormError('Les mots de passe ne correspondent pas.'));
        }
    }
}
