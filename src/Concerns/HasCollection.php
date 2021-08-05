<?php

namespace PayPal\Checkout\Concerns;

trait HasCollection
{
    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function __isset(string $key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Determine if a key exists on the items.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists(string $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
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
     * @param  string  $offset
     * @return void
     */
    public function offsetUnset(string $offset)
    {
        unset($this->items[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @param  string  $offset
     *
     */
    public function offsetGet(string $offset)
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * Determine if the purchase unit is empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}
