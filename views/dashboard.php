<?php include 'partials/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
        .portal-container {
            padding: 20px;
        }
        #student-table td[contenteditable="true"] {
            background-color: #f9f9f9;
        }
        .edit-btn, .delete-btn {
            margin-right: 5px;
        }
        .Capitalonly {
            text-transform: uppercase; 
        }
    </style>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#"><?php echo($_SESSION['name']); ?></a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <form id="logout-form" action="index.php?controller=auth&action=logout" method="post" style="display: none;">
                    <input type="hidden" name="logout" value="1">
                </form>
               <li class="nav-item">
                 <a class="nav-link" href="#" onclick="document.getElementById('logout-form').submit(); return false;">Logout</a>
                </li>
            </ul>
        </div>
</nav>

<div class="container portal-container">
        <h2>Student Listing</h2>
        <table id="student-table" class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr data-id="<?= $student['id'] ?>">
                    <td class="Capitalonly text-only editable"><?= htmlspecialchars($student['name']) ?></td>
                    <td class="Capitalonly text-only editable"><?= htmlspecialchars($student['subject']) ?></td>
                    <td class="number-only-contant editable"><?= htmlspecialchars($student['marks']) ?></td>
                    <td>
                        <button class="btn btn-primary edit-btn">Edit</button>
                        <button class="btn btn-danger delete-btn">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button id="add-student-btn" class="btn btn-success">Add New Student</button>
    </div>





<div class="modal fade" id="add-student-modal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add-student-form">
                        <div class="form-group">
                            <label for="new-name">Name</label>
                            <input type="text" class="form-control Capitalonly text-only" id="new-name" name="name" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <label for="new-subject">Subject</label>
                            <input type="text" class="form-control Capitalonly text-only" id="new-subject" name="subject" placeholder="Subject" required>
                        </div>
                        <div class="form-group">
                            <label for="new-marks">Marks</label>
                            <input type="number" class="form-control number-only" id="new-marks" name="marks"  placeholder="Marks" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<script  type="text/javascript" src="script.js"></script>
<script  type="text/javascript" src="custom.js"></script>

<?php include 'partials/footer.php'; ?>
