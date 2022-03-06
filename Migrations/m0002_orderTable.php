<?php

class m0002_orderTable{
    public function up()
    {
        $db = \App\Core\Application::$app->db;
        $sql = "CREATE TABLE orders(
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL, 
                purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                country VARCHAR(255),
                device VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=INNODB";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = \App\Core\Application::$app->dp;
        $sql = "DROP TABLE orders";
        $db->pro->exec($sql);

    }
}
