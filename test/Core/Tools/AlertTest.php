<?php

namespace Test\Core\Tools;

use Almacen\Core\Tools\Alert\Alert;
use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{
    protected AlertWrapper $alert;

    public function setUp(): void
    {
        $this->alert = AlertWrapper::getInstance('Test\Core\Tools\FakeAlertStorage');
    }

    public function tearDown(): void
    {
        $this->alert->getMessages();
    }

    /**
     * @test
     * @dataProvider  check_valid_levelsProvider
     * @param $input
     * @param $expected
     */
    public function
    check_valid_levels(
        $input,
        $expected
    ) {
        if ( ! $expected) {
            $this->expectException('\Exception');
        }

        $this->alert->exposedCheckLevelIsValid($input);
        $this->assertTrue(true);
    }

    public function check_valid_levelsProvider()
    {
        return [
            ['success', true],
            ['danger', true],
            ['warning', true],
            ['info', true],
            ['fake', false],
        ];
    }

    /** @test */
    public function
    add_one_message_and_retrieve_message()
    {
        $this->alert->add('Info message', 'info');
        $messages = $this->alert->getMessages('info');

        $this->assertEquals(['Info message'], $messages);
        $messages = $this->alert->getMessages('info');
        $this->assertEquals([], $messages);
    }

    /** @test */
    public function
    add_one_message_from_two_levels_and_retrieve_one()
    {
        $this->alert->add('Info message', 'info');
        $this->alert->add('Danger message', 'danger');
        $messages = $this->alert->getMessages('info');

        $this->assertEquals(['Info message'], $messages);
        $messages = $this->alert->getMessages('info');
        $this->assertEquals([], $messages);
    }

    /** @test */
    public function
    add_one_message_from_two_levels_and_retrieve_all()
    {
        $this->alert->add('Info message', 'info');
        $this->alert->add('Info message 2', 'info');
        $this->alert->add('Danger message', 'danger');
        $messages = $this->alert->getMessages();

        $this->assertEquals(['info' => ['Info message', 'Info message 2'], 'danger' => ['Danger message']], $messages);
        $messages = $this->alert->getMessages();
        $this->assertEquals([], $messages);
    }
}

class AlertWrapper extends Alert
{
    public function exposedCheckLevelIsValid($level): void
    {
        $this->checkLevelIsValid($level);
    }
}

