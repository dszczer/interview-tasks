<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Test;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait
{
    private Generator $faker;

    private function getFaker(): Generator
    {
        if (!isset($this->faker)) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }

    private static function getFakerStatic(): Generator
    {
        return Factory::create();
    }
}
