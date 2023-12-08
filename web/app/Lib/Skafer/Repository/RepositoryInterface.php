<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Skafer\Repository;

interface RepositoryInterface
{
    public static function all(): array;
    public static function get($key);
}
