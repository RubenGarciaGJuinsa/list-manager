<?php

namespace Test\Core\View;

use Almacen\Core\Application;
use Almacen\Core\View;
use Test\Core\Fake\FakeController;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    /** @test */
    public function
    get_controller_pathname_from_namespace()
    {
        $view = new ViewWrapper();
        $controllerPathName = $view->exposedGetControllerPathName('Fake\Namespace\FakeController');
        $this->assertEquals('fake', $controllerPathName);
    }

    /**
     * @test
     *
     * @dataProvider  get_view_file_from_view_nameProvider
     */
    public function
    get_view_file_from_view_name(
        $input,
        $expected
    ) {
        $view = new ViewWrapper();
        $controllerClass = 'Fake\Namespace\FakeController';
        $viewFile = $view->exposedGetViewFilePath($input, $controllerClass);
        $this->assertEquals($expected, $viewFile);
    }

    public function get_view_file_from_view_nameProvider()
    {
        $app = Application::getInstance();
        $basePath = $app->getBasePath();
        Application::delete();

        return
            [
                ['index', $basePath.'/test/Core/View/views/fake/index.php'],
                ['fake2/index', $basePath.'/test/Core/View/views/fake2/index.php'],
                ['/views/fake2/index.php', '/views/fake2/index.php'],
            ];
    }

    /** @test */
    public function
    render_file_not_nested()
    {
        $filePath = __DIR__.'/views/fake/not_nested_view.php';
        $params = ['param1' => 1, 'param2' => '2', 'param3' => 3];
        $view = new ViewWrapper();
        $renderedContent = $view->exposedRenderFile($filePath, $params);

        $this->assertEquals('View file:123', $renderedContent);
    }

    /** @test */
    public function
    render_layout()
    {
        $layoutPath = __DIR__.'/views/layouts/fake_layout.php';
        $viewContent = 'view content';
        $controller = new FakeController();
        $view = new ViewWrapper();

        $renderedContent = $view->renderLayout($layoutPath, $viewContent, $controller);

        $this->assertEquals('<layout>'.$viewContent.'</layout>', $renderedContent);
    }

    /** @test */
    public function
    render_not_nested()
    {
        $params = ['param1' => 1, 'param2' => '2', 'param3' => 3];
        $view = new ViewWrapper();
        $renderedContent = $view->render('not_nested_view', $params, 'Fake\Namespace\FakeController');

        $this->assertEquals('View file:123', $renderedContent);
    }

    /** @test */
    public function
    render_getting_not_readable_file()
    {
        $view = new ViewWrapper();
        $this->expectException('Almacen\Core\Exception\NotReadableViewFileException');
        $view->render('not_existing_file', []);
        $this->assertTrue(false, 'Exception not thrown');
    }

    /** @test */
    public function
    render_getting_not_valid_filename()
    {
        $view = new ViewWrapper();
        $this->expectException('Almacen\Core\Exception\IncorrectViewNameException');
        $view->render('', []);
        $this->assertTrue(false, 'Exception not thrown');
    }
}

class ViewWrapper extends View
{
    public function exposedGetViewFilePath(string $viewName, string $controllerClass): ?string
    {
        return $this->getViewFilePath($viewName, $controllerClass);
    }

    public function exposedGetControllerPathName(string $classWithNamespace)
    {
        return $this->getControllerPathName($classWithNamespace);
    }

    public function exposedRenderFile(string $viewFile, array $params)
    {
        return $this->renderFile($viewFile, $params);
    }

    protected function getViewBasePath(): string
    {
        return __DIR__.'/views/';
    }
}