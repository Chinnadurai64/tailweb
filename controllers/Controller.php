<?php

class Controller {
    public function __construct() {
        $this->setNoCacheHeaders();
    }

    protected function checkActiveSession() {
        if (!isset($_SESSION['teacher_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }
    protected function setNoCacheHeaders() {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

}
?>