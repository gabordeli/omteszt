<?php

declare(strict_types=1);

namespace Src\Calculator;

use Src\Calculator\Exception\RequiredAlwaysLowPointsInInputException;
use Src\Calculator\Exception\RequiredAlwaysMissingInInputException;
use Src\Calculator\Exception\RequiredOneRuleException;
use Src\Calculator\Exception\RequiredRuleException;
use Src\Calculator\Exception\RequiredRuleMissingInInputException;
use Src\Calculator\Exception\RuleCountException;
use Src\Calculator\Rules\RuleInterface;
use Src\Entity\ValueObject\ErettsegiEredmeny;

trait CalculatorValidatorTrait
{
    private static function validateValidatorsCount(): void
    {
        if (0 === \count(static::$rules)) {
            throw RuleCountException::create(static::class);
        }
    }

    private static function validateRequiredCalculatorOnInput(): void
    {
        $requiredRule = self::getRequiredRuleName();

        if (false === \array_key_exists($requiredRule, static::$rules)) {
            throw RequiredRuleException::create($requiredRule);
        }
    }

    private static function validateRequiredAlwaysOnInput(): void
    {
        $passed = 0;

        foreach (self::$inputData->getErettsegiEredmenyekCollection()->toArray() as $item) {
            /* @var ErettsegiEredmeny $item */
            if (true === \in_array($item->getTantargy(), self::$requiredAlways, true)) {
                ++$passed;
            }
        }

        if (\count(self::$requiredAlways) > $passed) {
            throw RequiredAlwaysMissingInInputException::create();
        }
    }

    private static function validateMinimumPointsOnInput(): void
    {
        foreach (self::$inputData->getErettsegiEredmenyekCollection()->toArray() as $item) {
            /* @var ErettsegiEredmeny $item */
            if ($item->getEredmeny() <= self::$minimumPoint) {
                throw RequiredAlwaysLowPointsInInputException::create($item->getTantargy()->value, static::$minimumPoint);
            }
        }
    }

    private static function validateRuleOnInput(): void
    {
        /** @var RuleInterface $rule */
        $rule = self::getRequiredRule();

        $passed = false;

        foreach (self::$inputData->getErettsegiEredmenyekCollection()->toArray() as $item) {
            /* @var ErettsegiEredmeny $item */
            if ($rule::getRequired() === $item->getTantargy()) {
                $passed = true;
            }
        }

        if (false === $passed) {
            throw RequiredRuleMissingInInputException::create($rule::getRequired()->value);
        }
    }

    private static function validateRuleRequiredOneOnInput(): void
    {
        /** @var RuleInterface $rule */
        $rule = self::getRequiredRule();

        $passed = false;

        foreach (self::$inputData->getErettsegiEredmenyekCollection()->toArray() as $item) {
            /* @var ErettsegiEredmeny $item */
            if (true === \in_array($item->getTantargy(), $rule::getRequiredOne(), true)) {
                $passed = true;
            }
        }

        if (false === $passed) {
            throw RequiredOneRuleException::create();
        }
    }
}
