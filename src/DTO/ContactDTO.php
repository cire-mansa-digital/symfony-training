<?php

namespace App\DTO;
use Symfony\Component\Validator\Constraints as Assert;


class ContactDTO {

    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 200,
    )]
   public $name='';

   #[Assert\Email()]
   #[Assert\NotBlank()]
   public $email='';

   #[Assert\NotBlank()]
   #[Assert\Length(
    min: 10,
    max: 200,
   )]
   public $message='';


   public  $service= '';


}
