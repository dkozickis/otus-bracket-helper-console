<?php

/*
 * This file is part of the bracket-helper project for otus.ru studies.
 *
 * (c) Deniss Kozickis <deniss.kozickis@gmail.com>
 *
 * Use and reuse as much as you want.
 * Distributed under Apache License 2.0
 */

namespace Dkozickis\Command;

use Dkozickis\BracketHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BracketHelperCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('check-file')
            ->setDescription('Проверяет соответствует ли содержимое файла условиям задания.')
            ->setHelp('Команда проверяте содержимое файла на соответсвие условию задания.')
            ->addArgument('file', InputArgument::REQUIRED, 'Файл, содержимое которого надо проверить');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bracketHelper = new BracketHelper();
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        if (!file_exists($file)) {
            $io->error('Такого файла нет');

            return;
        }

        $content = file_get_contents($file);

        //Если строка пустая, значит, что кол-во открытых и закрытых скобок было одинаково ;)
        if ('' === $content) {
            $io->success('Файл содержит ПРАВИЛЬНУЮ строку (пустая строка)');

            return;
        }

        try {
            $fileStatus = $bracketHelper->isValid($content);

            if (true === $fileStatus) {
                $io->success('Файл содержит ПРАВИЛЬНУЮ строку');
            } else {
                $io->error('Файл содержит НЕПРАВИЛЬНУЮ строку');
            }
        } catch (\InvalidArgumentException $exception) {
            $io->error('Файл содержит недопустимые символы');
        }
    }
}
