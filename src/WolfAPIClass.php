<?php

namespace IgnitionWolf\API;

class WolfAPIClass
{
    /**
     * Create a new WolfAPI Instance.
     */
    public function __construct()
    {
        // constructor body
    }

    /**
     * Friendly welcome.
     *
     * @param string $phrase Phrase to return
     * @return string Returns the phrase passed in
     */
    public function echoPhrase($phrase)
    {
        return $phrase;
    }
}
