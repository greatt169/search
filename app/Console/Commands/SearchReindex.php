<?php

namespace App\Console\Commands;

use App\Search\Index\Manager\Elasticsearch;
use Exception;
use Illuminate\Console\Command;
use App\Search\Index\Source\Elasticsearch as ElasticsearchSource;

class SearchReindex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

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
            $indexer = new Elasticsearch(
                new ElasticsearchSource()
            );
            $indexer->reindex();
            $this->info('full reindex is finished');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
