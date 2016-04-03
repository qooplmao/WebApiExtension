<?php

namespace Behat\WebApiExtension\PHPMatcher;

use Behat\WebApiExtension\PHPMatcher\Matcher\Pattern\Expander\Length;
use Behat\WebApiExtension\PHPMatcher\Matcher\Pattern\Expander\MaxLength;
use Behat\WebApiExtension\PHPMatcher\Matcher\Pattern\Expander\MinLength;
use Behat\WebApiExtension\PHPMatcher\Matcher\UUIDMatcher;
use Coduo\PHPMatcher\Factory\SimpleFactory as BaseSimpleFactory;
use Coduo\PHPMatcher\Lexer;
use Coduo\PHPMatcher\Matcher;
use Coduo\PHPMatcher\Parser;

class SimpleFactory extends BaseSimpleFactory
{
    /**
     * @return Matcher\ChainMatcher
     */
    protected function buildScalarMatchers()
    {
        $parser = $this->buildParser();

        return new Matcher\ChainMatcher(array(
            new UUIDMatcher(),
            new Matcher\CallbackMatcher(),
            new Matcher\ExpressionMatcher(),
            new Matcher\NullMatcher(),
            new Matcher\StringMatcher($parser),
            new Matcher\IntegerMatcher($parser),
            new Matcher\BooleanMatcher(),
            new Matcher\DoubleMatcher($parser),
            new Matcher\NumberMatcher(),
            new Matcher\ScalarMatcher(),
            new Matcher\WildcardMatcher()
        ));
    }

    /**
     * @return Parser
     */
    protected function buildParser()
    {
        $expanderInitializer = new Parser\ExpanderInitializer();

        $expanderInitializer->setExpanderDefinition('length', Length::class);
        $expanderInitializer->setExpanderDefinition('minLength', MinLength::class);
        $expanderInitializer->setExpanderDefinition('maxLength', MaxLength::class);

        return new Parser(new Lexer(), $expanderInitializer);
    }
}