<?php

namespace App\Form;

use App\Entity\Recipe;
use DateTimeImmutable;
use App\Entity\Category;
use PHPUnit\Event\Event;
use AsEntityAutocompleteField;
use AutocompleteChoiceTypeExtension;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label'=> 'titre'
            ])
            ->add('slug' , TextType::class, [
              'required'=>false,
            ])
            ->add('content')
            ->add('duration')
            // ->add('category', EntityType::class, [
            //     'class'=> Category::class,
            //     'choice_label'=> 'name',
            //     'autocomplete' => true

            // ])
            ->add('category', CategoryAutocompleteField::class)
            ->add('imageFile', FileType::class,[
                'required' => false,
            ])
            ->add('Ajouter', SubmitType::class, [
                'label'=> 'Enregistrer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT , $this->creupdate(...));
        ;
    }

    public function autoSlug( PreSubmitEvent $event ){
       $data = $event->getData();
    //    dd($data);
    if( empty($data['slug']) ){
        $slugger =  new AsciiSlugger();
       $data['slug'] = $slugger->slug($data['title'])->lower()->toString() ;
       $event->setData($data);
    }

    }
    public function creupdate(PostSubmitEvent $event){
        $data= $event->getData();
        if(!($data instanceof Recipe)){
            return ;
        }
        $data->setUpdateAt(new DateTimeImmutable);
        if (!$data->getId()) {
            $data->setCreatedAt(new DateTimeImmutable);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
