<?php

/*
 * This file is part of the bracket-helper project for otus.ru studies.
 *
 * (c) Deniss Kozickis <deniss.kozickis@gmail.com>
 *
 * Use and reuse as much as you want.
 * Distributed under Apache License 2.0
 */

namespace Dkozickis\Tests\Command;

use Dkozickis\Command\BracketHelperCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class BracketHelperCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ApplicationTester
     */
    private $applicationTester;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        file_put_contents(__DIR__ . '/text1.txt', '');
        file_put_contents(__DIR__ . '/text2.txt', ' ');
        file_put_contents(__DIR__ . '/text3.txt', '(())');
        file_put_contents(
            __DIR__ . '/text4.txt',
            '((
        ))   (()) ()'
        );
        file_put_contents(__DIR__ . '/text5.txt', '{}');
        file_put_contents(__DIR__ . '/text6.txt', '(');
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        unlink(__DIR__ . '/text1.txt');
        unlink(__DIR__ . '/text2.txt');
        unlink(__DIR__ . '/text3.txt');
        unlink(__DIR__ . '/text4.txt');
        unlink(__DIR__ . '/text5.txt');
        unlink(__DIR__ . '/text6.txt');
    }

    protected function setUp()
    {
        $application = new Application();
        $application->add(new BracketHelperCommand())
            ->getApplication()
            ->setDefaultCommand('check-file', true)
            ->setAutoExit(false);

        $this->applicationTester = new ApplicationTester($application);
    }

    /**
     * @dataProvider fileTestProvider
     *
     * @param mixed $file
     * @param mixed $result
     */
    public function testCommand($file, $result)
    {
        $this->applicationTester->run(
            ['file' => './tests/' . $file]
        );

        $consoleDisplay = $this->applicationTester->getDisplay();

        $this->assertContains($result, $consoleDisplay);
    }

    public function fileTestProvider()
    {
        return [
            ['text1.txt', 'OK'],
            ['text2.txt', 'OK'],
            ['text3.txt', 'OK'],
            ['text4.txt', 'OK'],
            ['text5.txt', 'ERROR'],
            ['text6.txt', 'ERROR'],
        ];
    }
}
