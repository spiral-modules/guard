<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Security\Bootloader;

use Spiral\Core\Bootloader\Bootloader;
use Spiral\Security\Guard;
use Spiral\Security\GuardInterface;
use Spiral\Security\PermissionManager;
use Spiral\Security\PermissionsInterface;
use Spiral\Security\RuleManager;
use Spiral\Security\RulesInterface;

/**
 * Security bootloader.
 */
class SecurityBootloader extends Bootloader
{
    const SINGLETONS = [
        PermissionsInterface::class => PermissionManager::class,
        RulesInterface::class       => RuleManager::class,
    ];

    const BINDINGS = [
        GuardInterface::class => Guard::class
    ];
}
