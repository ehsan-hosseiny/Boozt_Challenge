<?php

namespace App\Services;

use App\Interfaces\OrderRepositoryInterface;

class OrderRepositoryService implements OrderRepositoryInterface
{

    public function totalOrderCount()
    {
        return 'in service';
//        $queryBuilder = $this->model->getQueryBuilder();
//        $queryBuilder->count();
//        $result = $this->model->getFromQueryBuilder($queryBuilder);
//        return $result;
    }
}