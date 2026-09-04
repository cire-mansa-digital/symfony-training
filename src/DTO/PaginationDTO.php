<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class PaginationDTO
{


    public function __construct(
        #[Assert\Positive]
        private readonly  ?int $page=1
    )
    {
    }
}
