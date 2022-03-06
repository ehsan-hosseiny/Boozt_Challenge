<?php


namespace App\Models;

use App\Core\Application;
use App\Core\Model;

class Order extends Model
{
    public function rules(): array
    {
        return [];
    }

    public function totalOrder():int
    {
        $statement =  Application::$app->db->pdo->prepare("SELECT count(*) FROM orders ");
        $statement->execute();
        return intval($statement->fetchColumn());
    }



}