<?php


namespace App\Models;


use App\Core\Application;
use App\Core\Model;

class LoginForm extends Model
{
    public string $email = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRE, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRE],
        ];
    }

    public function labels():array
    {
        return [
          'email' => 'Your Email',
          'password' => 'Password',
        ];
        
    }

    public function login()
    {
        $user = User::findOne(['email'=>$this->email]);
        if(!$user){
            $this->addError('email','User does not exist with this email');
            return false;
        }

        if(!password_verify($this->password,$user->password)){
            $this->addError('password','Password is incorrect');
            return false;
        }

        return Application::$app->login($user);
    }

}