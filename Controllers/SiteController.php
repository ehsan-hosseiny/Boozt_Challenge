<?php
namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;

/**
 * Class SiteController
 * @author Ehsan Hosseiny ehsanhossini@gmail.com
 * @package App\Controllers
 */
class SiteController extends Controller
{
    public function home()
    {
        $params = [
            'name' => "PHP MVC"
        ];
        return $this->render('home',$params);
    }
}