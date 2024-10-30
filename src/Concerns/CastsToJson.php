<?php

namespace PayPal\Checkout\Concerns;

use PayPal\Checkout\Exceptions\JsonEncodingException;

trait CastsToJson
{
    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Convert the model instance to JSON.
     *
     *
     *
     * @throws JsonEncodingException
     */
    public function toJson(int $options = 0): string
    {
        $json = json_encode($this->toArray(), $options);

        if (json_last_error() !== JSON_ERROR_NONE || ! $json) {
            throw new JsonEncodingException(json_last_error_msg());
        }

        return $json;
    }
}
