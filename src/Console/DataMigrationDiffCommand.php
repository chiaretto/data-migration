<?php

namespace Fndmiranda\DataMigration\Console;

use Fndmiranda\DataMigration\Facades\DataMigration as FacadeDataMigration;
use Fndmiranda\DataMigration\Facades\DataMigration;
use Symfony\Component\Console\Helper\TableCell;

class DataMigrationDiffCommand extends DataMigrationCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data-migration:diff {migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show diff of each data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setMigration($this->argument('migration'));

        $this->getOutput()->writeln(sprintf('<comment>Calculating diff to %s:</comment>', $this->getMigration()->model()));
        $progressBar = $this->output->createProgressBar(count($this->getMigration()->data()));
        $progressBar->start();

        $data = DataMigration::diff($this->getMigration(), $progressBar)->toArray();
        $options = $this->getMigration()->options();
        $this->prepare($data, $options);

        $progressBar->finish();
        $this->getOutput()->newLine();

        $rows = $this->getRows();
        $relationships = $this->getRelationships();

        if (!count($rows) && !count($relationships)) {
            $this->info('Nothing to diff.');
        } else {
            $this->table($this->getHeaders($options['show']), $rows);

            foreach ($this->getRelationships() as $relationship => $data) {
                $headers = [
                    [new TableCell($relationship, ['colspan' => count($data['headers'])])],
                    $data['headers'],
                ];

                $this->table($headers, $data['rows']);
            }
        }
    }
}
