<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\ValueObject;

use App\SharedKernel\Domain\Utils;
use Faker\Factory;


abstract class JsonValueObject
{
    protected mixed $value;

    public function __construct(mixed $value)
    {
        if (is_string($value)) {
            $decodedValue = Utils::json_decode_with_booleans($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->value = $decodedValue;
            } else {
                $this->value = $value;
            }
        } else {
            $this->value = $value;
        }
    }

    final public function value(): string
    {
        return json_encode($this->value);
    }

    public function toArray(): array
    {
        return $this->value;
    }

    public static function random(int $maxDepth = 2): self
    {
        $faker = Factory::create();
        $randomData = self::generateRandomJson($maxDepth);
        return new static(json_encode($randomData));
    }

    private static function generateRandomJson(int $depth): array
    {
        $faker = Factory::create();
        if ($depth <= 1) {
            return ['key' => $faker->word];
        }

        return [
            'nested_key' => self::generateRandomJson($depth - 1),
            'random_word' => $faker->word,
        ];
    }
}
