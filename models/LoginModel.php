<?php
class LoginModel {
    private $valid_username = 'fiap';
    private $valid_password = 'fiap';

    public function authenticate($username, $password) {
        if ($username === $this->valid_username && $password === $this->valid_password) {
            return true;
        }
        return false;
    }
}
?>
