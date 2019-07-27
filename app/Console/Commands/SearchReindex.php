<?php

namespace App\Console\Commands;

use App\Search\Index\Manager\Elasticsearch;
use Exception;
use Illuminate\Console\Command;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;
use Illuminate\Support\Facades\Log;

class SearchReindex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex 
                            {data : The filepath of Json file with data. See swagger documentation} 
                            {--settings= : The filepath of Json file with settings and mapping. See swagger documentation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex index data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        try {
            $dataLink = $this->argument('data');
            $settingsLink = $this->option('settings');
            $indexer = new Elasticsearch(
                new ElasticsearchSource($dataLink, $settingsLink),
                new ElasticsearchEntity()
            );
            $indexer->reindex();
        } catch (Exception $e) {
            $channel = config('search.index.elasticsearch.dev_log_channel');
            Log::channel($channel)->error('%s In %s line %s', $e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
}
