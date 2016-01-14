<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Guard;

use Spiral\Core\ResolverInterface;

/**
 * Rule class provides ability to route check request to a specified method (by default check)
 * using resolver interface. As side effect check method will support method injections.
 *
 * Example:
 *
 * class MyRule extends Rule
 * {
 *      public function check($actor, $post)
 *      {
 *          return $post->author_id == $actor->id;
 *      }
 * }
 */
class Rule implements RuleInterface
{
    /**
     * Method to be used for checking.
     */
    const CHECK_METHOD = 'check';

    /**
     * @var ResolverInterface
     */
    protected $resolver = null;

    /**
     * Set of aliases to be used for method injection.
     *
     * @var array
     */
    protected $aliases = [
        'user' => 'actor'
    ];

    /**
     * @param ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param ActorInterface $actor
     * @param string         $permission
     * @param array          $context
     * @return bool
     */
    public function __invoke(ActorInterface $actor, $permission, array $context)
    {
        $parameters = [
                'actor'      => $actor,
                'permission' => $permission,
                'context'    => $context
            ] + $context;

        //Mounting aliases
        foreach ($this->aliases as $target => $alias) {
            $parameters[$target] = $parameters[$alias];
        }

        $method = new \ReflectionMethod($this, static::CHECK_METHOD);
        $method->setAccessible(true);

        return $method->invokeArgs($this, $this->resolver->resolveArguments($method, $parameters));
    }
}