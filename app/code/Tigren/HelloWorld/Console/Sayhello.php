<?php
/*
 * @author Trinhtheanh789
 * Copyright (c) 2023.  Trinhtheanh789. All rights reserved.
 */

namespace Tigren\HelloWorld\Console;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
class Sayhello extends Command
{
    protected function configure()
    {
        $this->setName('example:sayhello');
        $this->setDescription('Demo command line');
    }
    protected function execute(InputInterface $input, OutputInterface $output)  {
        $output->writeln("Hello World");
    }
}
