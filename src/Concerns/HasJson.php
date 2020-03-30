<?php

namespace PayPal\Checkout\Concerns;

use PayPal\Checkout\Exceptions\JsonEncodingException;

trait HasJson
{
    /**
     * Convert the model instance to JSON.
     *
     * @param int $options
     *
     * @return string
     *
     * @throws \PayPal\Checkout\Exceptions\JsonEncodingException
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->toArray(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonEncodingException(json_last_error_msg());
        }

        return $json;
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
