<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Shopify\Resource;

use Shopify\Rest\Base;

use function str_contains;

class Metaobject
{
    private const ID_FINDER_METHODS = [
        'findFromMetafieldObject',
        'findFromGidUri',
        'findDefault' // always last
    ];
    private const DEFAULT_GID_PATH = 'gid://shopify/Metaobject/';
    private mixed $mixedValue;
    private ?int $id = null;

    public function __construct(
        mixed $mixedValue
    )
    {
        $this->mixedValue = $mixedValue;
    }

    public static function find(mixed $value): ?array
    {
        $metaobject = new static($value);
        return $metaobject->findFromMixedValue();
    }

    private function findFromMixedValue(): ?array
    {
        $this->findId();
        return ($this->id) ? $this->fromId() : null;
    }

    private function findId(): void
    {
        array_map(function (string $methodName) {
            if (is_null($this->id)) {
                $this->$methodName();
            }
        }, self::ID_FINDER_METHODS);
    }

    private function fromId(): array
    {
        return ['metaoobject' => 'test'];
    }

    /**
     * --> Finder methods below
     */
    private function findDefault(): void
    {
        if (is_numeric($this->mixedValue)) {
            $this->id = (int)$this->mixedValue;
        }
    }

    private function findFromMetafieldObject(): void
    {
        if ($this->mixedValue instanceof Base) {
            $this->findFromGidUri($this->mixedValue->toArray()['value']);
        }
    }

    private function findFromGidUri(?string $uriPath = null): void
    {
        $uriPath = $uriPath ?? self::DEFAULT_GID_PATH;
        if (str_contains($this->mixedValue, $uriPath)) {
            $this->id = explode($uriPath, $this->mixedValue)[1];
        }
    }
}
