<?php

namespace PayPal\Checkout\Concerns;

use PayPal\Checkout\Orders\Item;

trait HasCollection
{
    /**
     * Determine if an attribute or relation exists on the model.
     *
     *
     * @return bool
     */
    public function __isset(string $key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Determine if a key exists on the items.
     */
    public function offsetExists(string $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Unset an attribute on the model.
     *
     *
     * @return void
     */
    public function __unset(string $key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @return void
     */
    public function offsetUnset(string $offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @return mixed|Item|null
     */
    public function offsetGet(string $offset)
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * Determine if the purchase unit is empty or not.
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}
