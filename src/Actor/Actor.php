<?php declare(strict_types=1);
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Security\Actor;

use Spiral\Security\ActorInterface;

/**
 * Simple actor with role dependency.
 */
class Actor implements ActorInterface
{
    /**
     * @var array
     */
    private $roles = [];

    /**
     * @param array $roles
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}