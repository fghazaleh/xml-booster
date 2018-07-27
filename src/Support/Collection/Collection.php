<?php declare(strict_types=1);

namespace FGhazaleh\Support\Collection;

use FGhazaleh\Support\Contracts\Arrayable;

/**
 * @package FGhazaleh\Support\Collection
 * @class Collection

 */
class Collection implements Arrayable, \JsonSerializable, \ArrayAccess, \Countable
{
    protected $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @inheritdoc
     * */
    public static function make(array $items = []): Collection
    {
        return new static($items);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            return null;
        }
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }


    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }


    public function count(): int
    {
        return count($this->items);
    }

    public function jsonSerialize(): string
    {
        return json_encode($this->items);
    }

    /**
     * @inheritdoc
     * */
    public function add($value, $key = null): void
    {
        if ($key != null) {
            $this->offsetSet($key, $value);
            return;
        }
        array_push($this->items, $value);
    }

    /**
     * @inheritdoc
     * */
    public function remove($key): void
    {
        $this->offsetUnset($key);
    }

    /**
     * @inheritdoc
     * */
    public function has($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * @inheritdoc
     * */
    public function get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @inheritdoc
     * */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Cast class attributes to array
     *
     * @return array
     * */
    public function toArray(): array
    {
        return array_map(function ($item) {
            if ($item instanceof Arrayable) {
                return $item->toArray();
            }
            return null;
        }, $this->items);
    }
}
