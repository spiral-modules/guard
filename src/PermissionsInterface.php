<?php
declare(strict_types=1);
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Security;

use Spiral\Security\Exception\PermissionException;
use Spiral\Security\Exception\RoleException;

/**
 * Class responsible for Role/Permission/Rule mapping.
 */
interface PermissionsInterface
{
    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool;

    /**
     * Register new role.
     *
     * @param string $role
     *
     * @throws RoleException
     */
    public function addRole(string $role);

    /**
     * Remove existed guard role and every association it has.
     *
     * @param string $role
     *
     * @throws RoleException
     */
    public function removeRole(string $role);

    /**
     * List of every known role.
     *
     * @return array
     */
    public function getRoles(): array;

    /**
     * Get list of all permissions and their rules associated with given role.
     *
     * @param string $role
     *
     * @return array
     *
     * @throws RoleException
     */
    public function getPermissions(string $role): array;

    /**
     * Get role/permission behaviour.
     *
     * @param string $role
     * @param string $permission
     *
     * @return RuleInterface
     *
     * @throws RoleException
     * @throws PermissionException
     */
    public function getRule(string $role, string $permission): RuleInterface;

    /**
     * Associate (allow) existed role with one or multiple permissions and specific behaviour.
     * Pattern based associations are supported using star syntax.
     *
     * $associations->allow('admin', '*', GuardInterface::ALLOW);
     * $associations->allow('user', 'posts.*', AuthorRule::class);
     *
     * Attention, role must be added previously!
     *
     * You can always create composite rules by creating decorating rule.
     *
     * @see GuardInterface::ALLOW
     * @see addRole()
     *
     * @param string $role
     * @param string $permission
     * @param string $rule Rule name previously registered in RulesInterface.
     *
     * @throws RoleException
     * @throws PermissionException
     */
    public function associate(
        string $role,
        string $permission,
        string $rule = 'Spiral\Security\Rules\AllowRule'
    );
}