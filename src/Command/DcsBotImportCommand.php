<?php

namespace App\Command;

use App\Perun\Service\DcsBotImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Command to import statistics from DCS Bot database into Perun.
 */
class DcsBotImportCommand extends Command
{
    protected static $defaultName = 'app:dcsbot:import';
    protected static $defaultDescription = 'Import statistics from DCS Bot database';

    private DcsBotImportService $importService;

    public function __construct(DcsBotImportService $importService)
    {
        parent::__construct();
        $this->importService = $importService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument(
                'server',
                InputArgument::OPTIONAL,
                'Server identifier for tracking sync state',
                'default'
            )
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'Run without persisting changes'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $chrono = new Stopwatch();

        $chrono->start('import');

        $serverId = $input->getArgument('server');
        $dryRun = $input->getOption('dry-run');

        if ($dryRun) {
            $io->note('Dry-run mode enabled - no changes will be persisted');
        }

        try {
            $imported = $this->importService->import($serverId, $output, $dryRun);
        } catch (\Exception $e) {
            $io->error(sprintf('Import failed: %s', $e->getMessage()));

            return Command::FAILURE;
        }

        $chrono->stop('import');

        $io->success(sprintf(
            'Import completed: %d records in %.2f seconds',
            $imported,
            $chrono->getEvent('import')->getDuration() / 1000
        ));

        return Command::SUCCESS;
    }
}
