<?php

class BaseController {

    /**
     * Inclui o header e o footer e renderiza a view principal.
     * Incluido apenas da dash e login para demosntracao 
     */
    protected function renderView($view, $data = []) {
        // Inclui o header
        include 'views/header.php';

        // Inclui a view principal
        include 'views/' . $view . '.php';

        // Inclui o footer
        include 'views/footer.php';
    }
}

?>
