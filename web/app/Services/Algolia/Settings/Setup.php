<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Settings;

use App\Services\Algolia\Framework\Config;
use App\Services\Algolia\Framework\Index;
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
            $this->initCurrentIndex($index);
            $settings = $this->currentIndex->algolia->getSettings();
            $config = Config::get(sprintf('indices.%s.settings.parameters', $this->currentIndex->app->code()), []);
            $this->currentIndex->algolia->setSettings(array_merge($settings, $config));
        }, $this->indices);
    }

    private function initCurrentIndex(IndexInterface $index): void
    {
        $this->currentIndex = new StdClass();
        $this->currentIndex->app = $index;
        $this->currentIndex->algolia = Index::use($index->code());
    }
}
