<?php

namespace App\DTO;

class CategoryWithNombre
{

    public function __construct(public int $id, public string $name, public string $slug, public int $total) {}
}
