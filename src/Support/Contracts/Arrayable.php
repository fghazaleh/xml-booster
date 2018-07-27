<?php declare(strict_types=1);

namespace FGhazaleh\Support\Contracts;

interface Arrayable
{

    /**
     * Cast class attributes to array
     *
     * @return array
     * */
    public function toArray(): array;
}
