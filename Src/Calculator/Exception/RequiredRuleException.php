<?php

declare(strict_types=1);

namespace Src\Calculator\Exception;

use Src\Transformer\Validator\Exception\ValidationException;

class RequiredRuleException extends ValidationException
{
    public static function create(string $required): self
    {
        return new self(sprintf(
            '[%s]. Az $input tartalmaz olyan adatot [%s] amihez a rule nincs betöltve! ',
            __CLASS__,
            $required
        ));
    }
}
