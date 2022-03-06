<?php


namespace App\Models;

use App\Core\Application;
use App\Core\Model;

class OrderItem extends Model
{
    public function rules(): array
    {
        return [];
    }

    public function totalRevenue():int
    {
        $statement =  Application::$app->db->pdo->prepare("SELECT sum(price)  FROM order_items ");
        $statement->execute();
        return intval($statement->fetchColumn());
    }



}