<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Security\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Security\Exception\PermissionException;
use Spiral\Security\Exception\RoleException;
use Spiral\Security\PermissionManager;
use Spiral\Security\Rule\AllowRule;
use Spiral\Security\Rule\ForbidRule;
use Spiral\Security\RulesInterface;

/**
 * Class PermissionManagerTest
 *
 * @package Spiral\Security\Tests
 */
class PermissionManagerTest extends TestCase
{
    const ROLE = 'test';
    const PERMISSION = 'permission';

    /**
     * @var RulesInterface
     */
    private $rules;

    public function setUp()
    {
        $this->rules = $this->createMock(RulesInterface::class);
    }

    public function testRoles()
    {
        $manager = new PermissionManager($this->rules);

        $this->assertFalse($manager->hasRole(static::ROLE));
        $this->assertEquals($manager, $manager->addRole(static::ROLE));
        $this->assertTrue($manager->hasRole(static::ROLE));
        $this->assertEquals($manager, $manager->removeRole(static::ROLE));
        $this->assertFalse($manager->hasRole(static::ROLE));

        $manager->addRole('one');
        $manager->addRole('two');
        $this->assertEquals(['one', 'two'], $manager->getRoles());
    }

    public function testAddRoleException()
    {
        $manager = new PermissionManager($this->rules);

        $this->expectException(RoleException::class);
        $manager->addRole(static::ROLE);
        $manager->addRole(static::ROLE);
    }

    public function testRemoveRoleException()
    {
        $manager = new PermissionManager($this->rules);

        $this->expectException(RoleException::class);
        $manager->removeRole(static::ROLE);
    }

    public function testAssociation()
    {
        $allowRule = new AllowRule();
        $forbidRule = new ForbidRule();

        $this->rules->method('has')->willReturn(true);
        $this->rules->method('get')
            ->withConsecutive([AllowRule::class], [AllowRule::class], [ForbidRule::class])
            ->willReturn($allowRule, $allowRule, $forbidRule);

        $manager = new PermissionManager($this->rules);
        $manager->addRole(static::ROLE);

        // test simple permission
        $this->assertEquals($manager, $manager->associate(static::ROLE, static::PERMISSION, AllowRule::class));
        $this->assertEquals($allowRule, $manager->getRule(static::ROLE, static::PERMISSION));

        // test pattern permission
        $this->assertEquals($manager, $manager->associate(static::ROLE, static::PERMISSION . '.*', AllowRule::class));
        $this->assertEquals($allowRule, $manager->getRule(static::ROLE, static::PERMISSION . '.' . static::PERMISSION));

        $this->assertEquals($manager, $manager->deassociate(static::ROLE, static::PERMISSION));
        $this->assertEquals($forbidRule, $manager->getRule(static::ROLE, static::PERMISSION));
    }

    public function testGetRuleRoleException()
    {
        $manager = new PermissionManager($this->rules);

        $this->expectException(RoleException::class);
        $manager->getRule(static::ROLE, static::PERMISSION);
    }

    public function testRulesForRoleException()
    {
        $this->rules->method('has')->willReturn(true);
        $manager = new PermissionManager($this->rules);

        $this->expectException(RoleException::class);
        $manager->getPermissions('admin');
    }

    public function testRulesForRole()
    {
        $this->rules->method('has')->willReturn(true);

        $manager = new PermissionManager($this->rules);

        $manager->addRole('admin');
        $manager->associate('admin', 'post.edit', AllowRule::class);

        $this->assertSame([
            'post.edit' => AllowRule::class
        ], $manager->getPermissions('admin'));
    }

    public function testGetFallbackRule()
    {
        $manager = new PermissionManager($this->rules);
        $manager->addRole(static::ROLE);

        $this->rules->method('get')
            ->withConsecutive([ForbidRule::class])
            ->willReturn(new ForbidRule());

        $this->assertInstanceOf(
            ForbidRule::class,
            $manager->getRule(static::ROLE, static::PERMISSION)
        );
    }

    public function testAssociateRoleException()
    {
        $manager = new PermissionManager($this->rules);

        $this->expectException(RoleException::class);
        $manager->associate(static::ROLE, static::PERMISSION);
    }

    public function testAssociatePermissionException()
    {
        $this->rules->method('get')->willReturn(false);

        $manager = new PermissionManager($this->rules);

        $manager->addRole(static::ROLE);
        $this->expectException(PermissionException::class);
        $manager->associate(static::ROLE, static::PERMISSION);
    }
}