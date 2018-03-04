<?php

namespace AlfredNutileInc\LaravelFeatureFlags;

use App\User;
use App\DripEmailer;
use Illuminate\Console\Command;
use AlfredNutileInc\LaravelFeatureFlags\ExportImportRepository;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ff:import_features {path_to_feature}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Point to a path and file relative to the command to import exported features from';


    /**
     * Create a new command instance.
     *
     * @param  DripEmailer  $drip
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
     */
    public function handle(ExportImportRepository $repo)
    {
        try {
            $this->info("Getting file");
            $file = \File::get($this->argument("path_to_feature"));
            $file = json_decode($file, true);
            $repo->import($file);
            $this->info("Imported FeatureFlags");
        } catch (\Exception $e) {
            \Log::error(sprintf("Error: %s", $e->getMessage()));
            $this->error("Error getting file and importing see logs");
        }

    }
}