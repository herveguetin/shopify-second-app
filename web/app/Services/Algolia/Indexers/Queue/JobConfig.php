<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers\Queue;

/**
 * @property string $indexer_code
 * @property array $objects
 */
class JobConfig
{
    public static array $allowedKeys = [
        'indexer_code',
        'objects'
    ];

    public function __construct(array $config)
    {
        foreach ($config as $k => $v) {
            $this->{$k} = $v;
        }
    }


    public static function encode(array $config): string
    {
        $sanitizedConfig = array_filter($config, function ($v, $k) {
            return in_array($k, JobConfig::$allowedKeys);
        }, ARRAY_FILTER_USE_BOTH);

        return json_encode($sanitizedConfig);
    }

    public static function fromConfig(string $config): JobConfig
    {
        $config = json_decode($config, true);
        return new static($config);
    }
}
