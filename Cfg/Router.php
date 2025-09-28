<?php

    namespace Cfg;

    use Phroute\Phroute\Dispatcher;
    use Phroute\Phroute\RouteCollector;

    class Router
    {
        /**
         * Заголовки
         *
         * @var string
         */
        private const PATH = 'path';
        private const METHOD = 'method';
        private const CONTROLLER = 'controller';
        private const ACTION = 'action';

        /**
         * Методы
         *
         * @var string
         */
        private const METHOD_GET = 'get';
        private const METHOD_POST = 'post';
        private const METHOD_PATCH = 'patch';
        private const METHOD_PUT = 'put';
        private const METHOD_DELETE = 'delete';
        private const METHOD_ANY = 'any';

        /**
         * Routers
         *
         * @var array
         */
        private $routers = [
    [self::CONTROLLER =>'Index', self::ACTION => 'index', self::PATH => "/", self::METHOD => self::METHOD_ANY],
    // Review routes
    [self::CONTROLLER =>'Review', self::ACTION => 'edit', self::PATH => "/review/edit/{id}", self::METHOD => self::METHOD_GET],
    [self::CONTROLLER =>'Review', self::ACTION => 'update', self::PATH => "/review/update", self::METHOD => self::METHOD_POST],
    [self::CONTROLLER =>'Review', self::ACTION => 'insert', self::PATH => "/review/insert", self::METHOD => self::METHOD_POST],
    [self::CONTROLLER =>'Review', self::ACTION => 'delete', self::PATH => "/review/delete", self::METHOD => self::METHOD_POST],
    // User routes
    [self::CONTROLLER =>'User', self::ACTION => 'edit', self::PATH => "/user/edit/{id}", self::METHOD => self::METHOD_GET],
    [self::CONTROLLER =>'User', self::ACTION => 'update', self::PATH => "/user/update", self::METHOD => self::METHOD_POST],
    [self::CONTROLLER =>'User', self::ACTION => 'insert', self::PATH => "/user/insert", self::METHOD => self::METHOD_POST],
    [self::CONTROLLER =>'User', self::ACTION => 'delete', self::PATH => "/user/delete", self::METHOD => self::METHOD_POST],
];

        /**
         *
         * @var string
         */
        private $path;

        /**
         *
         * @var RouteCollector
         */
        private $route;

        /**
         * @return void
         */
        public function __construct()
{
    $fullPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    
    if (isset($_GET['route'])) {
        $this->path = $_GET['route'];
    } else {
        // Для ЧПУ URL
        $this->path = str_replace('/Quest5', '', $fullPath);
    }
    
    if ($this->path === '') {
        $this->path = '/';
    }
    
    $this->route = new RouteCollector();
}

        /**
         * @return null|mixed
         */
        public function response()
        {
            $path = $this->getPath();
            $route = $this->getRoute();

            $this->recordRoutes();

            return (new Dispatcher($route->getData()))
                ->dispatch($_SERVER["REQUEST_METHOD"], $path);
        }

        /**
         *
         * @return void
         */
        private function recordRoutes(): void
        {
            $route = $this->getRoute();

            foreach ($this->routers as $router) {
                $handler = [
                    'Controllers\\' . $router[self::CONTROLLER] . 'Controller',
                    $router[self::ACTION] . 'Action'
                ];

                $router_path = $router[self::PATH];

                switch ($router[self::METHOD]) {
                    case self::METHOD_GET:
                        $route->get($router_path, $handler);
                    break;

                    case self::METHOD_POST:
                        $route->post($router_path, $handler);
                    break;

                    case self::METHOD_PATCH:
                        $route->patch($router_path, $handler);
                    break;

                    case self::METHOD_PUT:
                        $route->put($router_path, $handler);
                    break;

                    case self::METHOD_DELETE:
                        $route->delete($router_path, $handler);
                    break;

                    default:
                        $route->any($router_path, $handler);
                    break;
                }
            }
        }

        /**
         *
         * @return string
         */
        private function getPath(): string
        {
            return $this->path;
        }

        /**
         *
         * @return RouteCollector
         */
        private function getRoute(): RouteCollector
        {
            return $this->route;
        }

    }
