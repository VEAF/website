<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TeamspeakUsersCommand extends Command
{
    protected static $defaultName = 'app:teamspeak:users';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // IPv4 connection URI
            $uri = getenv('API_TEAMSPEAK_URL');
            //$uri = 'serverquery://website:e4fIJIGq@ts.veaf.org:10011/?port=9987&blocking=0';
            $uri = 'serverquery://website:TE9v1ce4@ts.veaf.org:10011/?port=9987&blocking=0&tls=0';
            echo $uri;
            // Create new object of TS3 PHP Framework class
            //$TS3PHPFramework = new \TeamSpeak3();

            // connect to above specified server, authenticate and spawn an object for the virtual server on port 9987
            // connect to local server, authenticate and spawn an object for the virtual server on port 9987
            //$ts3_VirtualServer = \TeamSpeak3::factory($uri);
            //$ts3_Channel = $ts3_VirtualServer->channelGetByName("Accueil");

            // connect to local server, authenticate and spawn an object for the virtual server on port 9987
            $ts3_VirtualServer = \TeamSpeak3::factory($uri);
            dump($ts3_VirtualServer);
            dump($ts3_VirtualServer->getId());
            dump($ts3_VirtualServer->getChildren());
// query clientlist from virtual server and filter by platform
            $arr_ClientList = $ts3_VirtualServer->clientList(array("client_platform" => "Android"));
// walk through list of clients
            foreach($arr_ClientList as $ts3_Client)
            {
                echo $ts3_Client . " is using " . $ts3_Client["client_platform"] . "<br />\n";
            }

// query clientlist from virtual server and filter by platform
            //$arr_ClientList = $ts3_VirtualServer->clientList([]);
// walk through list of clients
//            foreach($arr_ClientList as $ts3_Client)
//            {
//                echo $ts3_Client . " is using " . $ts3_Client["client_platform"] . "<br />\n";
//            }

            //echo $ts3_VirtualServer->getViewer(new \TeamSpeak3_Viewer_Html("images/viewericons/", "images/countryflags/", "data:image"));

            // spawn an object for the channel using a specified name
            // $ts3_Channel = $ts3_VirtualServer->channelGetByName("I do not exist");

            //$arr_ClientList = $ts3_VirtualServer->clientList(array("client_platform" => "Android"));
//            $arr_ClientList = $ts3_VirtualServer->clientListDb();
//            dump($arr_ClientList);


        } catch (\TeamSpeak3_Exception $e) {
            // print the error message returned by the server
            echo "Error " . $e->getCode() . ": " . $e->getMessage();

            return 1;
        }

        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
