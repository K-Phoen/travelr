<?php

declare(strict_types=1);

namespace Travelr;

final class GlobalConfig
{
    public const SORT_BY_NAME = 'name';
    public const SORT_BY_MODIFICATION_DATE = 'modification_date';

    /** @var string */
    private $sortImagesBy;

    public static function default(): self
    {
        return new static(self::SORT_BY_NAME);
    }

    public function __construct(string $sortImagesBy)
    {
        if (!\in_array($sortImagesBy, [self::SORT_BY_MODIFICATION_DATE, self::SORT_BY_NAME], true)) {
            throw new \DomainException('Invalid sort option');
        }

        $this->sortImagesBy = $sortImagesBy;
    }

    public function sortImagesBy(): string
    {
        return $this->sortImagesBy;
    }
}
