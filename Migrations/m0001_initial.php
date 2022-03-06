<?php

class m0001_initial{
    public function up()
    {
        $db = \App\Core\Application::$app->db;
        $sql = "CREATE TABLE users(
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                password VARCHAR(512) NOT NULL,
                status TINYINT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=INNODB";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = \App\Core\Application::$app->dp;
        $sql = "DROP TABLE users";
        $db->pro->exec($sql);

    }
}
