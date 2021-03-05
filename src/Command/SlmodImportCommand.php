<?php

namespace App\Command;

use App\Entity\Server;
use App\Service\SlmodImportService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class SlmodImportCommand extends Command
{
    protected static $defaultName = 'app:slmod:import';
    private EntityManager $entityManager;
    private SlmodImportService $importService;

    public function __construct(EntityManagerInterface $entityManager, SlmodImportService $importService)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->importService = $importService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import SLMOD stats from endpoint')
            ->addArgument('server', InputArgument::OPTIONAL, 'Server code (ex: public)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chrono = new Stopwatch();
        $chrono->start('import');

        $io = new SymfonyStyle($input, $output);
        $serverCode = $input->getArgument('server');

        $server = $this->entityManager->getRepository(Server::class)->findOneByCode($serverCode);
        if (null === $server) {
            $io->error(sprintf('Server code %s not found', $serverCode));

            return 1;
        }

        $output->writeln(sprintf('Import stats from server <info>%s</info>', $serverCode));
        $this->importService->import($server, $output);

        $chrono->stop('import');
        $io->success(sprintf('Stats imported in %d seconds', $chrono->getEvent('import')->getDuration() / 1000));

        return 0;
    }
}
