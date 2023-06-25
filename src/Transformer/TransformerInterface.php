<?php

namespace App\Transformer;

interface TransformerInterface
{
    public function transform(array $data): array;


    public function transformMany(array $data): array;
}
