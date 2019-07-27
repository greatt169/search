<?php

namespace App\Console\Commands;

use App\Search\Index\Manager\Elasticsearch;
use Exception;
use Illuminate\Console\Command;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;
use App\Search\Entity\Engine\Elasticsearch as ElasticsearchEntity;

class SearchReindex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex {data} {--settings=}';

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
        $dataLink = $this->argument('data');
        $settingsLink = $this->option('settings');
        $indexer = new Elasticsearch(
            new ElasticsearchSource($dataLink, $settingsLink),
            new ElasticsearchEntity()
        );
        try {
            $indexer->reindex();
        } catch (Exception $e) {
            if(isset($indexer)) {
                $indexer->log($e->getMessage(), 'error');
            }
        }
    }
}
