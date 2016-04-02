<?php

namespace Behat\WebApiExtension\PHPMatcher\Matcher;

use Coduo\PHPMatcher\Matcher\Matcher;
use Coduo\ToString\StringConverter;

final class UUIDMatcher extends Matcher
{
    const UUID_PATTERN  = '/^@uuid@$/';
    const PATTERN_REGEX = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/';

    /**
     * {@inheritDoc}
     */
    public function match($value, $pattern)
    {
        if (!preg_match(self::PATTERN_REGEX, $value)) {
            $this->error = sprintf("\"%s\" does not match \"%s\".", new StringConverter($value), new StringConverter(self::PATTERN_REGEX));
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function canMatch($pattern)
    {
        return is_string($pattern) && preg_match(self::UUID_PATTERN, $pattern);
    }
}