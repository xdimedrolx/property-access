<?php

namespace Bibop\PropertyAccessor\Metadata;

interface ObjectItemMetadataInterface
{
    public function className(): string;

    public function name(): string;

    public function isPublic(): bool;

    public function getterMethod(): ?string;

    public function setterMethod(): ?string;
}