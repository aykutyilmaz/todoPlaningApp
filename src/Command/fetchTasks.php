<?php
namespace App\Command;

use App\Service\Adapter\ProviderAlphaAdaptor;
use App\Service\Adapter\ProviderBetaAdaptor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\TaskService;

class fetchTasks extends Command
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;

        // you *must* call the parent constructor
        parent::__construct();
    }
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:fetch-tasks')

            // the short description shown while running "php bin/console list"
            ->setDescription('Fetches task.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to fetch tasks...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->taskService->getTasks(new ProviderAlphaAdaptor(), new ProviderBetaAdaptor());
        $output->writeln(['Tasks updated.']);
    }
}