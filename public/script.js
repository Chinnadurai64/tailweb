document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('student-table');
    const addStudentBtn = document.getElementById('add-student-btn');
    const addStudentForm = document.getElementById('add-student-form');

    table.addEventListener('click', function(event) {
        const target = event.target;
        const row = target.closest('tr');
        if (!row) return; 
        const id = row.getAttribute('data-id');
        if (target.matches('.delete-btn')) {
            handleDelete(row, id);
        } else if (target.matches('.update-btn')) {
            handleUpdate(target, row);
        } else if (target.matches('.edit-btn')) {
            handleEdit(target, row);
        }
    });

    function handleDelete(row, id) {
        fetch('index.php?controller=student&action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    row.remove();
                });
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error deleting student',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'An error occurred',
                showConfirmButton: false,
                timer: 1500
            });
        });
    }

    function handleUpdate(button, row) {
        const cells = row.querySelectorAll('td.editable');
        const updatedData = {
            id: row.getAttribute('data-id'),
            name: cells[0].textContent.trim(),
            subject: cells[1].textContent.trim(),
            marks: cells[2].textContent.trim()
        };
        fetch('index.php?controller=student&action=save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(updatedData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                });
                cells.forEach(cell => {
                    cell.contentEditable = false;
                    cell.classList.remove('edit-mode');
                });
                button.textContent = 'Edit';
                button.classList.remove('update-btn');
                button.classList.add('edit-btn');
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error updating student',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'An error occurred',
                showConfirmButton: false,
                timer: 1500
            });
        });
    }

    function handleEdit(button, row) {
        const cells = row.querySelectorAll('td.editable');

        cells.forEach(cell => {
            cell.contentEditable = true;
            cell.classList.add('edit-mode');
        });

        button.textContent = 'Update';
        button.classList.remove('edit-btn');
        button.classList.add('update-btn');
    }

    addStudentBtn.addEventListener('click', function() {
        $('#add-student-modal').modal('show');
    });
    addStudentForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const name = document.getElementById('new-name').value;
        const subject = document.getElementById('new-subject').value;
        const marks = document.getElementById('new-marks').value;

        fetch('index.php?controller=student&action=save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                name: name,
                subject: subject,
                marks: marks
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#add-student-modal').modal('hide');
                const table = document.getElementById('student-table').getElementsByTagName('tbody')[0];
                const newRow = table.insertRow();
                if(data.type === 'reupdate'){
                    console.log(data.id);
                    let existingRow = Array.from(table.rows).find(row => {
                        return row.getAttribute('data-id') === String(data.id);
                    });
                    if (existingRow) {
                        existingRow.cells[2].textContent = parseInt(existingRow.cells[2].textContent, 10) + parseInt(marks, 10);
                    } else {
                        console.error('Row not found for data-id:', data.id);
                    }
                }else{
                    newRow.setAttribute('data-id', data.id);  
                    newRow.innerHTML = `
                        <td class="Capitalonly text-only editable">${name}</td>
                        <td class="Capitalonly text-only editable">${subject}</td>
                        <td class="number-only editable">${marks}</td>
                        <td>
                            <button class="btn btn-primary edit-btn">Edit</button>
                            <button class="btn btn-danger delete-btn">Delete</button>
                        </td>
                    `;
                }
                document.getElementById('new-name').value = '';
                document.getElementById('new-subject').value = '';
                document.getElementById('new-marks').value = '';
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1500
                })
            } else {
                alert('Error adding student');
            }
        });
    });
});








