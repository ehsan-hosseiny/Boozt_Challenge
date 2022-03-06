<?php

namespace App\Core;

use App\Models\User;
use function Couchbase\defaultDecoder;

/**
 * Class Application
 *
 * @author ehsan hosseiny <ehsanhossini@gmail.com>
 * @package app\core
 */
class Application
{
    public static string $ROOT_DIR;

    public string $layout = 'main';
    public string $user_class;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public ?DbModel $user;
    public Session $session;
    public static Application $app;
    public ?Controller $controller = null;

    public function __construct($rootPath, array $config)
    {
        $this->user_class = $config['user_class'];

        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
        $primary_value = $this->session->get('user');
        if ($primary_value) {
            $primary_key = $this->user_class::primaryKey();
            $this->user = $this->user_class::findOne([$primary_key => $primary_value]);
        } else {
            $this->user = null;
        }
    }


    public static function isGuest()
    {
        return !self::$app->user;
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->router->renderView('_error', [
                'exception' => $e
            ]);
        }

    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(DbModel $user)
    {
        $this->user = $user;
        $primary_key = $user->primaryKey();
        $primary_value = $user->{$primary_key};
        $this->session->set('user', $primary_value);
        return true;
    }

    public function logOut()
    {
        $this->user = null;
        $this->session->remove('user');
    }


}