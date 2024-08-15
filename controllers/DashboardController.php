<!-- controller/DashboardController.php -->
<?php

class DashboardController extends BaseController{
    public function index() {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location:');
            exit();
        }

        $data = [];

        // Renderiza a view principal
        $this->renderView('dashboard', $data);
    }
}
