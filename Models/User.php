<?php


namespace App\Models;

use App\Core\Application;
use App\Core\UserModel;

class User extends UserModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $password = '';
    public int $status = self::STATUS_INACTIVE;
    public string $confirm_password = '';

    public function primaryKey():string
    {
        return 'id';
    }

    public function tableName():string
    {
        return 'users';
    }

    public function save()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash($this->password,PASSWORD_DEFAULT);

        return parent::save();
    }

    public function rules():array
    {
        return [
            'first_name' => [self::RULE_REQUIRE],
            'last_name' => [self::RULE_REQUIRE],
            'email' => [self::RULE_REQUIRE, self::RULE_EMAIL, [
                self::RULE_UNIQUE,'class' => self::class
                ]],
            'password' => [self::RULE_REQUIRE, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 24]],
            'confirm_password' => [self::RULE_REQUIRE, [self::RULE_MATCH, 'match' => 'password']],
        ];
        
    }

    public function attributes():array
    {
        return ['first_name','last_name','email','password','status'];
    }

    public function labels():array
    {
        return [
          'first_name'=>'First Name',
          'last_name'=>'Last Name',
          'email'=>'Email Address',
          'password'=>'Password',
          'confirm_password'=>'Confirm Password',
        ];
        
    }

    public function getDisplayName():string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function totalUser():int
    {
        $statement =  Application::$app->db->pdo->prepare("SELECT count(*) FROM users ");
        $statement->execute();
        return intval($statement->fetchColumn());
    }

}