<?php

namespace App\Command;

use App\Service\TeamSpeak3ClientCache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TeamSpeakScanCommand extends Command
{
    protected static $defaultName = 'app:team-speak:scan';

    private TeamSpeak3ClientCache $clientCache;

    public function __construct(TeamSpeak3ClientCache $clientCache)
    {
        $this->clientCache = $clientCache;
        parent::__construct(static::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Scan Team Speak Server informations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('scanning team speak server');

        $this->clientCache->putClients();
        $this->clientCache->putChannels();

        $output->writeln('team speak server scan done.');

        return 0;
    }
}
