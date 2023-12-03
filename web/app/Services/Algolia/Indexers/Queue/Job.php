<?php

namespace App\Services\Algolia\Indexers\Queue;

use Algolia\AlgoliaSearch\SearchIndex;
use App\Services\Algolia\Index;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Job implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private ?JobConfig $config;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $config)
    {
        $this->config = JobConfig::fromConfig($config);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $index = Index::use($this->config->indexer_code);
        $index->saveObjects($this->config->objects, ['objectIDKey' => 'id'])->wait();
    }
}
