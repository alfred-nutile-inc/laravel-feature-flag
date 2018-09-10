<?php

namespace FriendsOfCat\LaravelFeatureFlags\Console\Command;

use FriendsOfCat\LaravelFeatureFlags\FeatureFlag;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class SyncFlags extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature-flag:sync {--force} {--skip-cleanup}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync registered feature flags from the "laravel-feature-flag.sync_flags" config setting.';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        parent::__construct();

        $this->logger = $logger;
    }

    /**
     *
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Are you sure?')) {
            return;
        }

        $default_flags = (array) $this->getLaravel()
            ->make('config')
            ->get('laravel-feature-flag.sync_flags', []);

        foreach ($default_flags as $key => $default_value) {
            if (FeatureFlag::where('key', $key)->exists()) {
                $this->printAndLogLine(sprintf('Feature flag with key "%s" already exists', $key));
                continue;
            }

            // Otherwise, create the new flag with default value.
            $flag = $this->createFeatureFlag($key, $default_value);

            $this->printAndLogInfo(
                sprintf(
                    'Feature flag created with key "%s" [%d] with variant "%s"',
                    $key,
                    $flag->id,
                    json_encode($default_value)
                )
            );
        }

        if ($this->option('skip-cleanup')) {
            return;
        }

        // Tidy up and flags that are not present any more.
        $existing_keys = array_keys($default_flags);

        $query = FeatureFlag::whereNotIn('key', $existing_keys);

        if ($query->exists()) {
            $this->printAndLogLine(sprintf('Removing flags not defined in sync_flags'));

            $query->chunk(100, function ($chunk) {
                foreach ($chunk as $flag) {
                    $flag->delete();
                    $this->printAndLogLine(sprintf('Feature flag with key "%s" deleted', $flag->key));
                }
            });
        } else {
            $this->printAndLogLine(sprintf('No flags to remove'));
        }
    }

    private function printAndLogLine($line)
    {
        $this->line($line);
        $this->logger->info($line);
    }

    private function printAndLogInfo($line)
    {
        $this->info($line);
        $this->logger->info($line);
    }

    private function createFeatureFlag($key, $value)
    {
        $flag = new FeatureFlag();
        $flag->key = $key;
        $flag->variants = $value;
        $flag->save();

        return $flag;
    }
}
