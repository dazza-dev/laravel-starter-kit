<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ExampleJob;
use Illuminate\Console\Command;

/**
 * Ejemplo de comando que encola un job: cópialo y borra este.
 */
class ExampleCommand extends Command
{
    protected $signature = 'app:example {message=hola}';

    protected $description = 'Ejemplo: encola un job que solo escribe en el log';

    public function handle(): int
    {
        ExampleJob::dispatch($this->argument('message'));

        $this->info('Job encolado.');

        return self::SUCCESS;
    }
}
