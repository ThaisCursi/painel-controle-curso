<?php
require_once 'models/LoginModel.php';

class LoginController {
    private $model;

    public function __construct() {
        $this->model = new LoginModel();
        session_start(); // Inicie a sessão
    }

    public function showLoginForm() {
        // Verifica se o usuário já está autenticado
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
            header('Location: /dashboard');
            exit();
        }
        
        //Definido uma variável para mensagens de erro, se necessário
        $error_message = null;

        // Inclua a visão para o formulário de login
        include './views/login_form.php';
    }

    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';


        if ($this->model->authenticate($username, $password)) {
            // Define a variável de sessão para indicar que o usuário está autenticado
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;

            header('Location: /dashboard');
            exit();
        } else {
            $error_message = 'Usuário ou senha inválido.';
            include './views/login_form.php';
        }
    }

    public function logout() {
        session_start();
        session_unset(); 
        session_destroy(); 

        header('Location: index.php?rota=login');
        exit();
    }
}
