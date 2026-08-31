<?php

namespace App\Form;

use App\Entity\Category;

use App\Entity\Recipe;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;


class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('slug', TextType::class, [
                'required'=>false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter'
            ])
            ->addEventListener(FormEvents::POST_SUBMIT , $this->timespace(...))
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }

    public function autoSlug(PreSubmitEvent $event)
    {
        $slugger = new AsciiSlugger();
        $data = $event->getData();
        if (isset($data['slug']) || empty(trim($data['slug']))) {
            $data['slug'] =  $slugger->slug($data['name'])->lower()->toString();
            $event->setData($data);
        }
    }




    public function timespace(PostSubmitEvent $event)
    {
        $data = $event->getData();
        if (!($data instanceof Category)) {
            return;
        }

        $data->setUpdatedAt(new DateTimeImmutable);
        if (!$data->getId()) {
            $data->setCreatedAt(new DateTimeImmutable);
        }
    }
}
