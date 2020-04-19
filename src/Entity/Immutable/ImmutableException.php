<?php

namespace App\Entity\Immutable;

use BadMethodCallException;

/**
 * Exception class thrown when trying to modify an **immutable object**.
 *
 * @author caitlyn.osborne
 */
class ImmutableException extends BadMethodCallException
{
}