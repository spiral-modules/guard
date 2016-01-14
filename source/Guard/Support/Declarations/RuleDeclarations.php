<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Guard\Support\Declaration;

use Spiral\Guard\Rule;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

/**
 * Declares rule.
 */
class RuleDeclaration extends ClassDeclaration implements DependedInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            Rule::class => null
        ];
    }
}