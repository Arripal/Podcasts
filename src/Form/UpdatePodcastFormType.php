<?php

namespace App\Form;

use App\Entity\Category;
use App\Form\DataTransformer\StringTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;

class UpdatePodcastFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('file', FileType::class, [
                'label' => 'Fichier audio',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'audio/mpeg', //MP3
                            'audio/mp3', //MP4
                            'audio/wav', //WAV
                            'audio/ogg', //OGG
                        ],
                        'mimeTypesMessage' => 'Choississez un fichier audio valide'
                    ])
                ]
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

        $builder->get('file')->addModelTransformer(new StringTransformer());
    }
}
