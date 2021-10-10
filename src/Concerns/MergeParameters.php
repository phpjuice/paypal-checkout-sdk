<?php

namespace PayPal\Checkout\Concerns;

trait MergeParameters
{
    protected array $mergeParameters = [];

    /**
     * Merge given parameters with final request body.
     *
     * @param array $parameters
     * @return self
     */
    public function mergeWith(array $parameters): self
    {
        $this->mergeParameters = $parameters;
        return $this;
    }
}
