<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;

class CreatePodcastFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('file', FileType::class, [
                'label' => 'Fichier audio',
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/mp3',
                            'audio/wav',
                            'audio/ogg',
                        ],
                        'mimeTypesMessage' => 'Choississez un fichier audio valide'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add(
                'categories',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'label' => 'Catégories',
                    'multiple' => true,
                    'expanded' => true,
                    'required' => true,
                    'constraints' => [
                        new Count([
                            'max' => 2,
                            'maxMessage' => 'Vous ne pouvez pas choisir plus de 2 catégories.',
                            'min' => 1,
                            'minMessage' => 'Vous devez choisir au moins une catégorie.'
                        ])
                    ]
                ],
            )
        ;
    }
}
