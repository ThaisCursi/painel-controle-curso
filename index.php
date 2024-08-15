<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

// Define o caminho raiz da aplicação
define('ROOT_PATH','/');

// Inclua os arquivos dos controladores
require_once 'controllers/BaseController.php'; 
require_once 'controllers/LoginController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/AlunoController.php';
require_once 'controllers/TurmaController.php';
require_once 'controllers/MatriculaController.php';

// Inicie a sessão
session_start();

// Verifica se o usuário está autenticado
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    // Redireciona para a página solicitada se autenticado
    $rota = $_GET['rota'] ?? 'dashboard';
} else {
    // Se não estiver autenticado, define a rota como login
    $rota = $_GET['rota'] ?? 'login';
}

// Define uma função para incluir a página 404
function pageNotFound() {
    http_response_code(404);
    require 'views/404.php';
    exit();
}


switch ($rota) {
    case 'login':
        $controller = new LoginController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;

    case 'logout':
        $controller = new LoginController();
        $controller->logout();
        break;

    case 'dashboard':
        
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'cadastrar_aluno':
        $controller = new AlunoController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_id_aluno'])) {
                $controller->update();
            } else {
                $controller->store();
            }
        } else if (isset($_GET['delete_id_aluno'])) {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;
    case 'cadastrar_turma':
        $controller = new TurmaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_id_turma'])) {
                $controller->update();
            } else {
                $controller->store();
            }
        } else if (isset($_GET['delete_id_turma'])) {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;

    case 'fazer_matricula':
        $controller = new MatriculaController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_id_matricula'])) {
                $controller->update();
            } else {
                $controller->store();
            }
        } else if (isset($_GET['delete_id_matricula'])) {
            $controller->delete();
        } else {
            $controller->index();
        }
        break;
    default:
        pageNotFound();
        echo "Rota não encontrada!";
        break;
}
