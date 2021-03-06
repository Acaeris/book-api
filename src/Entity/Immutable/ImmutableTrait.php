<?php

namespace App\Entity\Immutable;

use App\Entity\Cloneable\CloneNotSupportedTrait;

/**
 * The trait {@see ImmutableTrait} helps to implement the **Immutable Object**
 * implementation pattern.
 *
 * The trait implements the following:
 *
 * * Avoids setting of inaccessible properties (`$obj->value = 'foo'`).
 * * Avoids calling the constructor on an object directly
 *   (`$obj->__construct()`).
 *
 * @author caitlyn.osborne
 */
trait ImmutableTrait
{
    use CloneNotSupportedTrait;

    /**
     * `true` if the object using this trait has been constructed already,
     * `false` otherwise.
     *
     * @var boolean
     * @todo Find a solutions that does not require a member attribute.
     */
    private $constructed = false;

    /**
     * Throws an {@see ImmutableException}.
     *
     * Always throws an {@see ImmutableException} when trying to set an
     * inaccessible property.
     *
     * @param string $name  Not used.
     * @param mixed  $value Not used.
     *
     * @return void
     * @throws ImmutableException Always.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    final public function __set($name, $value)
    {
        $this->throwImmutableException();
    }

    /**
     * Throws an {@see ImmutableException}.
     *
     * @return void
     * @throws ImmutableException Always.
     */
    final private function throwImmutableException()
    {
        throw new ImmutableException('The object is immutable.');
    }

    /**
     * Initializes a new instance of the class using the {@see ImmutableTrait}.
     *
     * Throws an {@see ImmutableException} if the object using the {@see
     * ImmutableTrait} has been constructed already.
     *
     * @throws ImmutableException If the object using the {@see ImmutableTrait}
     *    has been constructed already.
     */
    public function __construct()
    {
        if (true === $this->constructed) {
            $this->throwImmutableException();
        }
        $this->constructed = true;
    }
}