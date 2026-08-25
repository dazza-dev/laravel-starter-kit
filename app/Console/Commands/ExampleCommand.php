<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ExampleJob;
use Illuminate\Console\Command;

/**
 * Example command that queues a job: copy it and delete this one.
 */
class ExampleCommand extends Command
{
    protected $signature = 'app:example {message=hello}';

    protected $description = 'Example: queues a job that only writes to the log';

    public function handle(): int
    {
        ExampleJob::dispatch($this->argument('message'));

        $this->info('Job queued.');

        return self::SUCCESS;
    }
}
