<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

// Check if the user is a teacher
$is_teacher = false;
if($user_id != '') {
    $check_role = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $check_role->execute([$user_id]);
    $user_role = $check_role->fetchColumn();
    if($user_role == 'teacher') {
        $is_teacher = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- quick select section starts  -->

<section class="quick-select">

   <h1 class="heading">Quick options</h1>

   <div class="box-container">

      <?php if($is_teacher): ?>
      <!-- Only show options for teachers -->
      <div class="box" style="text-align: center;">
         <h3 class="title">Manage Notifications</h3>
         <div class="flex-btn" style="padding-top: .5rem;">
            <a href="#" class="option-btn">Add Notification</a>
            <a href="#" class="option-btn">Edit Notification</a>
            <a href="#" class="option-btn">Delete Notification</a>
         </div>
      </div>
      <?php endif; ?>

      <!-- Display notifications for students -->
      <?php if(!$is_teacher && $user_id != ''): ?>
      <div class="box">
         <h3 class="title">Notifications: </h3>
         <?php
         $select_notifications = $conn->prepare("SELECT * FROM `notifications` ORDER BY created_at DESC LIMIT 5");
         $select_notifications->execute();
         if ($select_notifications->rowCount() > 0) {
             while ($fetch_notification = $select_notifications->fetch(PDO::FETCH_ASSOC)) {
                 // Display notifications
                 echo "<p class='notification'>" . $fetch_notification['notification'] . " (Created: " . $fetch_notification['created_at'] . ")</p>";
             }
         } else {
             echo '<p class="notification">No notifications available.</p>';
         }
         ?>
      </div>
      <?php endif; ?>

      <!-- Login options for users -->
      <?php if($user_id == ''): ?>
      <div class="box" style="text-align: center;">
         <h3 class="title">Login for students: </h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">Login</a>
         </div>
      </div>


      <div class="box" style="text-align: center;">
         <h3 class="title">Login for teachers: </h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="admin/login.php" class="option-btn">Login</a>
         </div>
      </div>

      <?php endif; ?>

      <div class="box" style="text-align: center;">
         <h3 class="title">Assignments: </h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="courses.php" class="option-btn"> View Assignments: </a>
         </div>
      </div>

   </div>

</section>

<!-- quick select section ends -->

<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">Latest courses</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
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
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">View course</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Login to view Courses</p>';
      }
      ?>

   </div>

   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">View more</a>
   </div>

</section>

<!-- courses section ends -->

<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>
