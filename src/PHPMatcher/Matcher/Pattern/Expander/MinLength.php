<?php

namespace Behat\WebApiExtension\PHPMatcher\Matcher\Pattern\Expander;

use Coduo\PHPMatcher\Matcher\Pattern\PatternExpander;
use Coduo\ToString\StringConverter;

final class MinLength implements PatternExpander
{
    /**
     * @var null|string
     */
    private $error;

    /**
     * @var integer
     */
    private $min;

    /**
     * @param int $min
     */
    public function __construct($min)
    {
        $this->min = $min;
    }

    /**
     * @param $value
     * @return boolean
     */
    public function match($value)
    {
        if (!is_array($value) && !is_string($value)) {
            $this->error = sprintf("MinLength expander requires \"array\" or \"string\", got \"%s\".", new StringConverter($value));
            return false;
        }

        if (is_string($value)) {
            return $this->matchString($value);
        }

        if (is_array($value)) {
            return $this->matchArray($value);
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Match string length
     *
     * @param $value
     * @return bool
     */
    private function matchString($value)
    {
        $length = strlen($value);

        if ($this->min > $length) {
            $this->error = sprintf(
                "%s is less than \"%s\" characters long.",
                new StringConverter($value),
                new StringConverter($this->min)
            );

            return false;
        }

        return true;
    }

    /**
     * Match array length
     *
     * @param array $value
     * @return bool
     */
    private function matchArray(array $value)
    {
        $count = count($value);

        if ($this->min > $count) {
            $this->error = sprintf(
                "%s has less than \"%s\" elements.",
                new StringConverter($value),
                new StringConverter($this->min)
            );

            return false;
        }

        return true;
    }
}