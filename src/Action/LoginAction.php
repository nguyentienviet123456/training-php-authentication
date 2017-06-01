<?php

namespace App\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Container;

class LoginAction
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        // TODO: write login action code
        if(isset($_POST['submit'])){
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $username = mysqli_escape_string($con,$username);
        $password = mysqli_escape_string($con,$password);


        //Database connection
        $con = mysqli_connect('localhost','root','Jarious','register_and_login');
        $error = false;



        if (!$con) {
             die("Connection failed: " . mysqli_connect_error());
        }           

        $userchecker = mysqli_query($con,"SELECT * FROM users WHERE user_name = '$username'");


        if(empty($username) || mb_strlen($username) < 5){
            echo "Username is too short or empty." . '<br />';
            $error = true;

        }

        if(empty($password) || mb_strlen($password) < 5){
            echo 'Password is too short or empty. <br />';
            $error = true;
        }

        if(mysqli_num_rows($userchecker) > 1){
            echo 'Username is already taken.';
            $еrror = true;
        }

        if(!$error){
            $password = md5($password);
            $query = "INSERT INTO users(user_name,pass_word) VALUES ('$username', '$password')";
            $useradd = mysqli_query($con,$query);
        }

    }
    }

    /**
     * @return array
     */
    protected function getInputs()
    {
        return (array) $this->request->getParsedBody();
    }

    /**
     * @param string $username
     * @return array|bool
     */
    protected function getUserByUsername($username)
    {
        $users = $this->app->get('users')['form'];

        foreach ($users as $user) {
            if ($username === $user['username']) {
                return $user;
            }
        }

        return false;
    }

    /**
     * @param string $url
     * @param int    $statusCode
     * @return Response
     */
    protected function redirectTo($url, $statusCode = 301)
    {
        return $this->response->withStatus($statusCode)
            ->withHeader('Location', $url);
    }
}