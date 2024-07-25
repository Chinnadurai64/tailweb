<?php
require_once '../controllers/Controller.php';
require_once '../helpers/db.php';
require_once '../helpers/helper.php';

class AuthController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function login() {
        if (isset($_SESSION['teacher_id'])) {
            header('Location: index.php?controller=student&action=index');
            exit();
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        include '../views/login.php';
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['errors'][] = 'Invalid Request!';
                header('Location: index.php?controller=auth&action=login');
                exit();
            }

            $pdo = Database::connect();
            $username = $_POST['username'];
            $password = Helper::cryptoJsAesDecrypt($_POST['str'] . $_POST['csrf_token'], $_POST['password']);
            $errors = Helper::validateInput($username, $password);

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header('Location: index.php?controller=auth&action=login');
                exit();
            }

            $query = $pdo->prepare("SELECT * FROM teachers WHERE username = ?");
            $query->execute([$username]);
            $user = $query->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['teacher_id'] = $user['id'];
                $_SESSION['name'] = $user['username'];
                header('Location: index.php?controller=student&action=index');
                exit();
            } else {
                $_SESSION['errors'][] = 'Invalid credentials or user not found.';
                header('Location: index.php?controller=auth&action=login');
                exit();
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
}
?>
