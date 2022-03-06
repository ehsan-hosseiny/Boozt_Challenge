<?php


namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\middlewares\AuthMiddleware;
use App\Core\Request;
use App\Core\Response;
use App\Models\LoginForm;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;


class AuthController extends Controller
{

    public function __construct( )
    {
        $this->registerMiddleware(new AuthMiddleware(['dashboard']));
    }

    public function login(Request $request, Response $response)
    {
        $login_form = new LoginForm();
        if ($request->isPost()) {
            $login_form->loadData($request->getBody());
            if ($login_form->validate() && $login_form->login()) {
                $response->redirect('/');
            }
        }
        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $login_form
        ]);
    }

    public function register(Request $request)
    {
        $user = new User();
        if ($request->isPost()) {

            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlash('success', 'Thanks for register');
                Application::$app->response->redirect('/');
                exit;
            }

            return $this->render('register', [
                'model' => $user
            ]);

        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logOut();
        $response->redirect('/');
    }

    public function dashboard()
    {
        $user = new User();
        $order = new Order();
        $order_item = new OrderItem();

        return $this->render('dashboard', [
            'total_customer' => $user->totalUser(),
            'total_order' => $order->totalOrder(),
            'total_revenue' => $order_item->totalRevenue()
        ]);

    }

}