<?php

namespace Behat\WebApiExtension\PHPMatcher\Matcher\Pattern\Expander;

use Coduo\PHPMatcher\Matcher\Pattern\PatternExpander;
use Coduo\ToString\StringConverter;

final class MaxLength implements PatternExpander
{
    /**
     * @var null|string
     */
    private $error;

    /**
     * @var integer
     */
    private $max;

    /**
     * @param int $max
     */
    public function __construct($max)
    {
        $this->max = $max;
    }

    /**
     * @param $value
     * @return boolean
     */
    public function match($value)
    {
        if (!is_array($value) && !is_string($value)) {
            $this->error = sprintf("Length expander requires \"array\" or \"string\", got \"%s\".", new StringConverter($value));
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

        if ($this->max < $length) {
            $this->error = sprintf(
                "%s is more than \"%s\" characters long.",
                new StringConverter($value),
                new StringConverter($this->max)
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

        if ($this->max < $count) {
            $this->error = sprintf(
                "%s has more than \"%s\" elements.",
                new StringConverter($value),
                new StringConverter($this->max)
            );

            return false;
        }

        return true;
    }
}