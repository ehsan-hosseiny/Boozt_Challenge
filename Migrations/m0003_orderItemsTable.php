<?php

class m0003_orderItemsTable{
    public function up()
    {
        $db = \App\Core\Application::$app->db;
        $sql = "CREATE TABLE order_items(
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                EAN VARCHAR(100),
                quantity INT NOT NULL,
                price DECIMAL(10,2),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=INNODB";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = \App\Core\Application::$app->dp;
        $sql = "DROP TABLE order_items";
        $db->pro->exec($sql);

    }
}
