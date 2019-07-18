<?php

namespace App\Console\Commands;

use App\Helpers\Timer;
use App\Search\Index\Listeners\SourceListener;
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
    protected $signature = 'search:reindex {--link=}';

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
        $sourceLink = '/var/www/public/data_test.json';
        $timer = new Timer();
        $timer->start('test');
        $listener = new SourceListener(function ($items): void {
            dump('butch readed');
            //
        });

        $stream = fopen($sourceLink, 'r');
        try {
            $parser = new \JsonStreamingParser\Parser($stream, $listener);
            $parser->parse();
            fclose($stream);
        } catch (Exception $e) {
            fclose($stream);
            throw $e;
        }


        $formatBytes = function($bytes, $precision = 2) {
            $units = array("b", "kb", "mb", "gb", "tb");

            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            $bytes /= (1 << (10 * $pow));

            return round($bytes, $precision) . " " . $units[$pow];
        };
        $timer->end('test');
        print $formatBytes(memory_get_peak_usage()); echo PHP_EOL;
        print $timer->getInterval('test'); echo PHP_EOL;

        /*try {
            $sourceLink = $this->option('link');
            $indexer = new Elasticsearch(
                new ElasticsearchSource($sourceLink),
                new ElasticsearchEntity()
            );
            $indexer->reindex();
            $displayResultMessages = $indexer->getDisplayResultMessages();
            foreach ($displayResultMessages as $message) {
                $this->info($message);
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }*/
    }
}
