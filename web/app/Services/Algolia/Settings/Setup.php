<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Settings;

use App\Services\Algolia\App\Config;
use App\Services\Algolia\App\Index;
use App\Services\Algolia\Index\IndexInterface;
use App\Services\Algolia\Index\IndexRepository;
use StdClass;

class Setup
{
    /**
     * @var IndexInterface[] $indices
     */
    private array $indices = [];
    private ?StdClass $currentIndex = null;

    public function __construct(
        ?IndexInterface $index
    )
    {
        $this->indices = $index ? [$index] : IndexRepository::all();
    }

    public static function push(?IndexInterface $index = null): void
    {
        $setup = new static($index);
        $setup->run();
    }

    public function run(): void
    {
        array_map(function (IndexInterface $index) {
            $settings = $index->algolia()->getSettings();
            $config = Config::get(sprintf('indices.%s.settings.parameters', $index->code()), []);
            $this->currentIndex->algolia->setSettings(array_merge($settings, $config));
        }, $this->indices);
    }
}
