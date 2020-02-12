<?php

namespace Test\Core\Controller;

use Test\Core\Application\ApplicationWrapper;
use Test\Core\Fake\FakeController;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    /** @test */
    public function
    get_action_content()
    {
        $controller = new FakeController();
        $content = $controller->run('actionIndex', [1, 2, 3]);
        $this->assertEquals('returnValue123', $content);
    }

    /** @test */
    public function
    set_layout()
    {
        $controller = new FakeController();
        $expectedLayout = 'layoutDePega';
        $controller->setLayout($expectedLayout);
        $layout = $controller->getLayout();

        $this->assertEquals($expectedLayout, $layout);
    }

    /** @test */
    public function
    render_view_without_layout()
    {
        $controller = new FakeController();
        $app = ApplicationWrapper::getInstance();

        $html = $controller->exposedRender($app->getBasePath().'/test/Core/View/views/fake/simple_view.php');

        $this->assertEquals('View content', $html);
    }

    /** @test */
    public function
    render_view_with_layout()
    {
        $controller = new FakeController();
        $app = ApplicationWrapper::getInstance();

        $controller->setLayout($app->getBasePath().'/test/Core/View/views/layouts/fake_layout.php');
        $html = $controller->exposedRender($app->getBasePath().'/test/Core/View/views/fake/simple_view.php');

        $this->assertEquals('<layout>View content</layout>', $html);
    }
}
