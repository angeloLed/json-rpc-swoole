<?php

namespace App\Transformer;

class OilPriceTrendTransformer implements TransformerInterface
{
    public function transform(array $data): array
    {
        return [
            'price' => $data['Brent Spot Price'],
            'dateISO8601' => $data['Date']
        ];
    }

    public function transformMany(array $data): array
    {
        return array_map(fn (array $item) => $this->transform($item), $data);
    }
}
