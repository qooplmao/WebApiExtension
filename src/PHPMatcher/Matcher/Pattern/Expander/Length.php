<?php

namespace Behat\WebApiExtension\PHPMatcher\Matcher\Pattern\Expander;

use Coduo\PHPMatcher\Matcher\Pattern\PatternExpander;
use Coduo\ToString\StringConverter;

final class Length implements PatternExpander
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
     * @var integer
     */
    private $max;

    /**
     * @param int $min
     * @param int|null $max
     */
    public function __construct($min, $max = null)
    {
        $this->min = (int) $min;
        $this->max = null !== $max ? (int) $max : null;
    }

    /**
     * @param $value
     * @return boolean
     */
    public function match($value)
    {
        if (null !== $this->max && $this->max <= $this->min) {
            $this->error = sprintf("Length expander requires \"min\" to be less than \"max\", min is \"%s\" by max is \"%s\".", new StringConverter($this->min), new StringConverter($this->max));
            return false;
        }


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

        if (null === $this->max && $this->min !== $length) {
            $this->error = sprintf(
                "%s isn't \"%s\" characters long.",
                new StringConverter($value),
                new StringConverter($this->min)
            );

            return false;
        }

        if ($this->min > $length || $this->max < $length) {
            $this->error = sprintf(
                "%s isn't between \"%s\" and \"%s\" characters long.",
                new StringConverter($value),
                new StringConverter($this->min),
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

        if (null === $this->max && $this->min !== $count) {
            $this->error = sprintf(
                "%s doesn't have \"%s\" elements.",
                new StringConverter($value),
                new StringConverter($this->min)
            );

            return false;
        }

        if ($this->min > $count || $this->max < $count) {
            $this->error = sprintf(
                "%s isn't between \"%s\" and \"%s\" elements.",
                new StringConverter($value),
                new StringConverter($this->min),
                new StringConverter($this->max)
            );

            return false;
        }

        return true;
    }
}