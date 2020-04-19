<?php

namespace App\Entity\Cloneable;

use \Exception;

/**
 * Thrown to indicate that the magic `__clone` method in a class has been called
 * to clone an object, but that the object's class does not implement the {@see
 * CloneableInterface}.
 *
 * Applications that override the `__clone` method can also throw this exception
 * to indicate that an object could not or should not be cloned.
 *
 * @author caitlyn.osborne
 */
class CloneNotSupportedException extends Exception
{
}