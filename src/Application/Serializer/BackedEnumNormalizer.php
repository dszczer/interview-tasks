<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Serializer;

use BackedEnum;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use ValueError;

use function is_int;
use function is_string;

#[AutoconfigureTag('serializer.normalizer')]
class BackedEnumNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @throws NotNormalizableValueException
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): BackedEnum
    {
        if (!is_subclass_of($type, BackedEnum::class)) {
            throw new InvalidArgumentException('The data must belong to a backed enumeration.');
        }

        if (!is_int($data) && !is_string($data)) {
            throw NotNormalizableValueException::createForUnexpectedDataType('The data is neither an integer nor a string, you should pass an integer or a string that can be parsed as an enumeration case of type '.$type.'.', $data, [Type::BUILTIN_TYPE_INT, Type::BUILTIN_TYPE_STRING], $context['deserialization_path'] ?? null, true);
        }

        try {
            return $type::from($data);
        } catch (ValueError) {
            throw new InvalidArgumentException('The data must belong to a backed enumeration of type '.$type);
        }
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return is_string($data) || is_int($data);
    }

    public function normalize(mixed $object, string $format = null, array $context = []): int|string
    {
        if (!is_subclass_of($object, BackedEnum::class)) {
            throw new InvalidArgumentException('The data must belong to a backed enumeration.');
        }

        return $object->value;
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return is_subclass_of($data, BackedEnum::class);
    }
}
