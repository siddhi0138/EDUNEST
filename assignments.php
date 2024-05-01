<?php
// Include necessary files and initialize database connection
include 'components/connect.php';

// Check if the user is logged in
if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
    $user_type = $_COOKIE['user_id'];
} else {
    // Redirect the user to the login page if not logged in
    header('Location: login.php');
    exit();
}

// Handle form submission for creating assignments (only for teachers)
if($user_type == 'teacher' && isset($_POST['create_assignment'])){
    // Sanitize and fetch user input
    $experiment_number = filter_var($_POST['experiment_number'], FILTER_SANITIZE_NUMBER_INT);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Prepare and execute SQL to insert new assignment
    $insert_assignment = $conn->prepare("INSERT INTO `assignments` (experiment_number, description) VALUES (?, ?)");
    $insert_assignment->execute([$experiment_number, $description]);
   
    // Redirect back to the same page after adding the assignment
    header('Location: assignments.php');
    exit();
}

// Fetch assignments from the database
$select_assignments = $conn->prepare("SELECT * FROM `assignments` ORDER BY experiment_number ASC");
$select_assignments->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Assignments</title>
   <!-- Add your CSS links here -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="assignments">
   <h1 class="heading">Assignments</h1>

   <?php if($user_type == 'teacher'): ?>
   <!-- Form for creating assignments (only for teachers) -->
   <form action="" method="post">
      <label for="experiment_number">Experiment Number:</label>
      <input type="number" id="experiment_number" name="experiment_number" required><br>
      <label for="description">Description:</label><br>
      <textarea id="description" name="description" required></textarea><br>
      <input type="submit" name="create_assignment" value="Create Assignment">
   </form>
   <?php endif; ?>

   <div class="box-container">
      <?php
         if($select_assignments->rowCount() > 0){
            while($assignment = $select_assignments->fetch(PDO::FETCH_ASSOC)){
               // Display assignment details
      ?>
      <div class="box">
         <h3 class="experiment-title">Experiment <?= $assignment['experiment_number']; ?></h3>
         <p><?= $assignment['description']; ?></p>
         <?php if($user_type == 'student'): ?>
         <!-- Submit button for each experiment (only for students) -->
         <a href="submit_assignment.php?assignment_id=<?= $assignment['id']; ?>" class="submit-btn">Submit</a>
         <?php endif; ?>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No assignments available.</p>';
         }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<!-- Add your JavaScript file link here -->
<script src="js/script.js"></script>
</body>
</html>
