<?php

namespace App\Entity\Cloneable;

/**
 * A class uses the {@see CloneNotSupportedTrait} to indicate to the magic
 * `__clone` method of a class that it is **illegal** for that method to make a
 * field-for-field copy of instances of that class.
 *
 * Invoking the magic `__clone` method on an instance that uses the {@see
 * CloneNotSupportedTrait} results in the exception {@see
 * CloneNotSupportedException} being thrown.
 *
 * @author caitlyn.osborne
 */
trait CloneNotSupportedTrait
{
    /**
     * Throws a {@see CloneNotSupportedException}.
     *
     * @return void
     * @throws CloneNotSupportedException Always.
     */
    final public function __clone()
    {
        throw new CloneNotSupportedException;
    }
}