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
   <title>Courses</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      .assignment-box {
         border: 1px solid #ccc;
         padding: 10px;
         margin-bottom: 10px;
         position: relative;
      }
      .assignment-box:hover {
         cursor: pointer;
         background-color: #f0f0f0;
      }
      .assignment-details {
         display: none;
         padding: 10px;
      }
      .submit-btn {
         margin-top: 10px;
      }
      /* New styles for grade visibility */
      .grade {
         margin-top: 5px;
      }
      p, h3{
         font-size: 1.5rem;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- courses section starts  -->
<section class="courses">

   <h1 class="heading">All Courses</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC");
         $select_courses->execute(['active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">View Course</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Login to view Courses</p>';
      }
      ?>

   </div>

</section>
<!-- courses section ends -->

<!-- Assignments section starts here -->
<section class="assignments">
   <h1 class="heading">Assignments</h1>
   <div class="box-container">
      <?php if($user_type === 'teacher'): ?>
      <!-- Only display this part if the user is a teacher -->
      <a href="create_assignment.php" class="create-btn">Create Assignment</a>
      <?php endif; ?>
      <?php if($select_assignments->rowCount() > 0): ?>
      <?php while($assignment = $select_assignments->fetch(PDO::FETCH_ASSOC)): ?>
      <div class="assignment-box">
         <h3 class="experiment-title"><?= $assignment['title']; ?></h3>
         <p><?= $assignment['description']; ?></p>
         <div class="assignment-details">
            <p>Due Date: <?= $assignment['due_date']; ?></p>
            <p>Submission Type: <?= $assignment['submission_type']; ?></p>
            <!-- Display grade if available -->
            <?php if (!empty($assignment['grade'])): ?>
               <p class="grade">Grade: <?= $assignment['grade']; ?></p>
            <?php endif; ?>
            <input type="hidden" name="assignment_id" value="<?= $assignment['id']; ?>">
            <button type="submit" class="submit-btn"><a href="assignment.php">Submit</a></button>
         </div>
         <?php if($user_type === 'student'): ?>
         <!-- Only display this part if the user is a student -->
         <?php endif; ?>
      </div>
      <?php endwhile; ?>
      <?php else: ?>
      <p class="empty">No assignments available.</p>
      <?php endif; ?>
   </div>
</section>
<!-- Assignments section ends here -->

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
<script>
   // Script to toggle visibility of assignment details upon clicking on assignment box
   const assignmentBoxes = document.querySelectorAll('.assignment-box');
   assignmentBoxes.forEach(box => {
      box.addEventListener('click', () => {
         const details = box.querySelector('.assignment-details');
         details.style.display = (details.style.display === 'block') ? 'none' : 'block';
      });
   });
</script>
   
</body>
</html>
