<?php

declare (strict_types=1);
namespace RectorPrefix20210607\Doctrine\Inflector\Rules\English;

use RectorPrefix20210607\Doctrine\Inflector\Rules\Patterns;
use RectorPrefix20210607\Doctrine\Inflector\Rules\Ruleset;
use RectorPrefix20210607\Doctrine\Inflector\Rules\Substitutions;
use RectorPrefix20210607\Doctrine\Inflector\Rules\Transformations;
final class Rules
{
    public static function getSingularRuleset() : \RectorPrefix20210607\Doctrine\Inflector\Rules\Ruleset
    {
        return new \RectorPrefix20210607\Doctrine\Inflector\Rules\Ruleset(new \RectorPrefix20210607\Doctrine\Inflector\Rules\Transformations(...\RectorPrefix20210607\Doctrine\Inflector\Rules\English\Inflectible::getSingular()), new \RectorPrefix20210607\Doctrine\Inflector\Rules\Patterns(...\RectorPrefix20210607\Doctrine\Inflector\Rules\English\Uninflected::getSingular()), (new \RectorPrefix20210607\Doctrine\Inflector\Rules\Substitutions(...\RectorPrefix20210607\Doctrine\Inflector\Rules\English\Inflectible::getIrregular()))->getFlippedSubstitutions());
    }
    public static function getPluralRuleset() : \RectorPrefix20210607\Doctrine\Inflector\Rules\Ruleset
    {
        return new \RectorPrefix20210607\Doctrine\Inflector\Rules\Ruleset(new \RectorPrefix20210607\Doctrine\Inflector\Rules\Transformations(...\RectorPrefix20210607\Doctrine\Inflector\Rules\English\Inflectible::getPlural()), new \RectorPrefix20210607\Doctrine\Inflector\Rules\Patterns(...\RectorPrefix20210607\Doctrine\Inflector\Rules\English\Uninflected::getPlural()), new \RectorPrefix20210607\Doctrine\Inflector\Rules\Substitutions(...\RectorPrefix20210607\Doctrine\Inflector\Rules\English\Inflectible::getIrregular()));
    }
}
