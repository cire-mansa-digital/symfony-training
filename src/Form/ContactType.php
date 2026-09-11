<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use function Symfony\Component\Translation\t;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label'=> t('contactForm.name')
            ])
            ->add('email',EmailType::class, [
                'label'=> t('contactForm.email')
            ])
            ->add('service',  ChoiceType::class, [
                'label'=> t('contactForm.services'),
                'choices'=> [
                    'compta'=> 'compta@gmail.com',
                    'marketing'=> 'marketign@gmail.com',
                    'dev'=> 'dev@gmail.com'

                ],
            ])
            ->add('message', TextareaType::class,[
                'label'=> t('contactForm.messages'),
                'empty_data'=> '',

            ])
            ->add('save', SubmitType::class, [
                'label'=> t('contactForm.submit')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }


}
