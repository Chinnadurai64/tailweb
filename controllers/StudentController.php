<?php
require_once '../controllers/Controller.php';
require_once '../helpers/db.php';

class StudentController extends Controller {
    private const RESPONSE_TEMPLATE = ['success' => false, 'message' => ''];
    private $pdo;

    public function __construct() {
        parent::__construct();
        $this->pdo = Database::connect();
        $this->checkActiveSession();
    }

    public function index() {
        $query = $this->pdo->prepare("SELECT * FROM students WHERE teacher_id = ? and deleted_at IS NULL");
        $query->execute([$_SESSION['teacher_id']]);
        $students = $query->fetchAll();
        include '../views/dashboard.php';
    }

    public function save() {
        $response = self::RESPONSE_TEMPLATE;
        $id = $_POST['id'] ?? null;
        $name = strtoupper($_POST['name']);
        $subject = strtoupper($_POST['subject']); 
        $marks = $_POST['marks'];
        try {
            if ($id) {
                $query = $this->pdo->prepare("UPDATE students SET name = ?, subject = ?, marks = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $query->execute([$name, $subject, $marks, $id]);
                $response['message'] = 'Student Details Updated Successfully!';
                $response['type'] = 'update';
            } else {
                $query = $this->pdo->prepare("SELECT id, marks FROM students WHERE name = ? AND subject = ?");
                $query->execute([$name, $subject]);
                $student = $query->fetch();

                if ($student) {
                    $new_marks = $student['marks'] + $marks;
                    $query = $this->pdo->prepare("UPDATE students SET marks = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                    $query->execute([$new_marks, $student['id']]);
                    $response['message'] = 'Existing Student Details Updated Successfully!';
                    $response['type'] = 'reupdate';
                    $response['id'] =  $student['id'];

                } else {
                    $teacher_id = $_SESSION['teacher_id'];
                    $query = $this->pdo->prepare("INSERT INTO students (name, subject, marks, teacher_id, created_at, updated_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
                    $query->execute([$name, $subject, $marks,$teacher_id]);
                    $response['message'] = 'New Student Details Created Successfully!';
                    $response['id'] = $this->pdo->lastInsertId();
                    $response['type'] = 'insert';
                }
            }
            $response['success'] = true;
        } catch (Exception $e) {
            $response['message'] = 'Error: ' . $e->getMessage();
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function delete() {
        $response = self::RESPONSE_TEMPLATE;
        $id = $_POST['id'];

        try {
            $query = $this->pdo->prepare("UPDATE students SET deleted_at = CURRENT_TIMESTAMP WHERE id = ?");
            $query->execute([$id]);
            $response['success'] = true;
            $response['message'] = 'Student Detail Deleted Successfully!';
        } catch (Exception $e) {
            $response['message'] = 'Error: ' . $e->getMessage();
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>
