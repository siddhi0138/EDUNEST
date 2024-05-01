<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Deletion functionality
if(isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $delete_assignment = $conn->prepare("DELETE FROM assignments WHERE id = ?");
    $delete_assignment->execute([$delete_id]);
    $message[] = 'Assignment deleted successfully.';
}

if(isset($_POST['submit'])){

   $id = unique_id();
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $due_date = $_POST['due_date'];
   $due_date = filter_var($due_date, FILTER_SANITIZE_STRING);
   $submission_type = $_POST['submission_type'];
   $submission_type = filter_var($submission_type, FILTER_SANITIZE_STRING);

   $add_assignment = $conn->prepare("INSERT INTO `assignments` (id, tutor_id, title, description, due_date, submission_type, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
   $add_assignment->execute([$id, $tutor_id, $title, $description, $due_date, $submission_type, $status]);
   $message[] = 'New assignment added!';

}

// Fetch existing assignments
$get_assignments = $conn->prepare("SELECT * FROM assignments WHERE tutor_id = ?");
$get_assignments->execute([$tutor_id]);
$assignments = $get_assignments->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Assignment</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="assignment-form">

   <h1 class="heading">Add New Assignment</h1>

   <form action="" method="post">
      <p>Assignment status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- Select status</option>
         <option value="active">Active</option>
         <option value="inactive">Inactive</option>
      </select>
      <p>Assignment title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter assignment title" class="box">
      <p>Assignment description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write assignment description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>Due date <span>*</span></p>
      <input type="date" name="due_date" class="box">
      <p>Submission type <span>*</span></p>
      <select name="submission_type" class="box" required>
         <option value="" selected disabled>-- Select submission type</option>
         <option value="online">Online</option>
         <option value="offline">Offline</option>
      </select>
      <input type="submit" value="Add Assignment" name="submit" class="btn">
   </form>

</section>

<section class="existing-assignments">
   <h2>Existing Assignments</h2>
   <?php
   foreach ($assignments as $assignment) {
       echo '<div class="assignment">';
       echo '<h3>' . $assignment['title'] . '</h3>';
       echo '<p>' . $assignment['description'] . '</p>';
       echo '<p>Due Date: ' . $assignment['due_date'] . '</p>';
       echo '<form action="" method="post">';
       echo '<input type="hidden" name="delete_id" value="' . $assignment['id'] . '">';
       echo '<button type="submit" name="delete" class="btn delete-btn" onclick="return confirm(\'Are you sure you want to delete this assignment?\')">Delete</button>';
       echo '</form>';
       echo '</div>';
   }
   ?>
</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
