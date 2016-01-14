<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Guard\Entities;

/**
 * Provides ability to process permissions as star based patterns.
 *
 * Example: post.*
 */
class StarPatterns
{
    /**
     * @param string $string
     * @return bool
     */
    public function isPattern($string)
    {
        return strpos($string, '*') !== false;
    }

    /**
     * @param string $string
     * @param string $pattern
     * @return bool
     */
    public function matches($string, $pattern)
    {
        if (!$this->isPattern($pattern)) {
            return false;
        }

        return (bool)preg_match($this->getRegex($pattern), $string);
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function getRegex($pattern)
    {
        $regex = str_replace('*', '[a-z0-9_\-]+', addcslashes($pattern, '.-'));

        return "#^{$regex}$#i";
    }
}