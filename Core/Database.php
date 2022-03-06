<?php


namespace App\Core;


use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Faker\Factory;

class Database
{
    public \PDO $pdo;
    protected $count = 10;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR.'/Migrations');
        $toApplyMigrations =  array_diff($files,$appliedMigrations);
        foreach ($toApplyMigrations as $migration){
            if($migration === '.' || $migration == '..'){
                continue;
            }

            require_once  Application::$ROOT_DIR.'/Migrations/'.$migration;
            $className =  pathinfo($migration,PATHINFO_FILENAME);
            $instance = new $className();
            echo $this->log("Applying migration $migration".PHP_EOL);
            $instance->up();
            echo $this->log("Applied migration $migration".PHP_EOL);
            $newMigrations[] = $migration;
        }

        if(!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        }else{
            echo $this->log("All migrations are applied");
        }

    }

    public function Seeder()
    {
       $faker = Factory::create();
       $this->createAdmin();



        for ($k = 0; $k < $this->count; $k++) {
            $status = random_int(0, 1);
            $user_statement = $this->pdo->prepare("INSERT INTO users (email, first_name, last_name,password,status) VALUES
            ('{$faker->email}','{$faker->firstName}','{$faker->lastName}','{$faker->word}','{$status}')
            ");
            $user_statement->execute();

            for ($i = 0; $i < 5; $i++) {
                $last_user_id = $this->getLastRowId('users');
                $order_statement = $this->pdo->prepare("INSERT INTO orders (user_id, purchase_date, country,device) VALUES
                ('{$last_user_id}','{$faker->date()}','{$faker->word}','{$faker->word}')
                ");
                $order_statement->execute();


                for ($j = 0; $j < 5; $j++) {
                    $last_order_id = $this->getLastRowId('orders');
                    $quantity = random_int(1, 50);
                    $price = $faker->randomFloat(2, 1, 10000);
                    $order_statement = $this->pdo->prepare("INSERT INTO order_items (order_id, EAN, quantity,price) VALUES
                    ('{$last_order_id}','{$faker->ean13}','{$quantity}','{$price}')
                    ");
                    $order_statement->execute();
                }
            }
        }
    }

    private function createAdmin()
    {
        $admin_pass = password_hash('12345678',PASSWORD_DEFAULT);
        $admin = $this->pdo->prepare("INSERT INTO users (email, first_name, last_name,password,status) VALUES 
            ('ehsanhossini@gmail.com','ehsan','hosseiny','{$admin_pass}','1')
            ");
        $admin->execute();
    }

    private function getLastRowId($table='users')
    {
        $statement =  $this->pdo->prepare("SELECT id FROM {$table} ORDER BY id DESC LIMIT 1");
        $statement->execute();
        $row = $statement->fetch();
        if ($row) {
            return $row['id'];
        }

    }

    public function createMigrationsTable()
    {

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=INNODB;
        ");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        $str =implode(",", array_map(fn($m)=>"('$m')",$migrations));

        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES 
            $str
            ");
        $statement->execute();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }


    protected function log($message)
    {
        echo '['.date('Y-m-d H:i:s') . '] - ' , $message.PHP_EOL;
    }


}