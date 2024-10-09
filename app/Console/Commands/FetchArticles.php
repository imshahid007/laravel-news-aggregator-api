<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ArticleAggregatorService;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store articles from multiple news APIs';

    /**
     * Execute the console command.
     */
    public function handle(ArticleAggregatorService $articleAggregatorService): void
    {
        $articleAggregatorService->aggregateArticles();
        $this->info('Articles fetched and stored successfully.');
    }
}
