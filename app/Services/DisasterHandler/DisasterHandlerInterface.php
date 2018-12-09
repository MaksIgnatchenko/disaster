<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 05.12.2018
 *
 */

namespace App\Services\DisasterHandler;

interface DisasterHandlerInterface
{
    /**
     * Making a request to api.
     *
     * @return DisasterHandlerInterface
     */
    public function request() : DisasterHandlerInterface;

    /**
     * Parse response and return array of result items
     *
     * @return array
     */
    public function getResult() : array;

    /**
     * Set additional options for api parser.
     *
     * @param array $options
     */
    public function setOptions(array $options) : void;
}
