<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:dashboard.php');
}

if(isset($_POST['update'])){

   $video_id = $_POST['video_id'];
   $video_id = filter_var($video_id, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $playlist = $_POST['playlist'];
   $playlist = filter_var($playlist, FILTER_SANITIZE_STRING);

   $update_content = $conn->prepare("UPDATE `content` SET title = ?, description = ?, status = ? WHERE id = ?");
   $update_content->execute([$title, $description, $status, $video_id]);

   if(!empty($playlist)){
      $update_playlist = $conn->prepare("UPDATE `content` SET playlist_id = ? WHERE id = ?");
      $update_playlist->execute([$playlist, $video_id]);
   }

   $old_thumb = $_POST['old_thumb'];
   $old_thumb = filter_var($old_thumb, FILTER_SANITIZE_STRING);
   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   if(!empty($thumb)){
      if($thumb_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $update_thumb = $conn->prepare("UPDATE `content` SET thumb = ? WHERE id = ?");
         $update_thumb->execute([$rename_thumb, $video_id]);
         move_uploaded_file($thumb_tmp_name, $thumb_folder);
         if($old_thumb != '' AND $old_thumb != $rename_thumb){
            unlink('../uploaded_files/'.$old_thumb);
         }
      }
   }

   $old_video = $_POST['old_video'];
   $old_video = filter_var($old_video, FILTER_SANITIZE_STRING);
   $video = $_FILES['video']['name'];
   $video = filter_var($video, FILTER_SANITIZE_STRING);
   $video_ext = pathinfo($video, PATHINFO_EXTENSION);
   $rename_video = unique_id().'.'.$video_ext;
   $video_tmp_name = $_FILES['video']['tmp_name'];
   $video_folder = '../uploaded_files/'.$rename_video;

   if(!empty($video)){
      $update_video = $conn->prepare("UPDATE `content` SET video = ? WHERE id = ?");
      $update_video->execute([$rename_video, $video_id]);
      move_uploaded_file($video_tmp_name, $video_folder);
      if($old_video != '' AND $old_video != $rename_video){
         unlink('../uploaded_files/'.$old_video);
      }
   }

   $message[] = 'Content updated!';

}elseif(isset($_POST['update_assignment'])){

   $assignment_id = $_POST['assignment_id'];
   $assignment_id = filter_var($assignment_id, FILTER_SANITIZE_STRING);
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
   $grade = $_POST['grade'];
   $grade = filter_var($grade, FILTER_SANITIZE_NUMBER_INT);

   $update_assignment = $conn->prepare("UPDATE `assignments` SET title = ?, description = ?, status = ?, due_date = ?, submission_type = ?, grade = ? WHERE id = ?");
   $update_assignment->execute([$title, $description, $status, $due_date, $submission_type, $grade, $assignment_id]);

   $message[] = 'Assignment updated!';

}elseif(isset($_POST['add_notification'])){

   $notification = $_POST['notification'];
   $notification = filter_var($notification, FILTER_SANITIZE_STRING);

   $insert_notification = $conn->prepare("INSERT INTO `notifications` (tutor_id, notification) VALUES (?, ?)");
   $insert_notification->execute([$tutor_id, $notification]);

   $message[] = 'Notification added!';

}elseif(isset($_POST['edit_notification'])){

   $edited_notification = $_POST['edited_notification'];
   $edited_notification = filter_var($edited_notification, FILTER_SANITIZE_STRING);
   $notification_id = $_POST['notification_id'];
   $notification_id = filter_var($notification_id, FILTER_SANITIZE_STRING);

   $update_notification = $conn->prepare("UPDATE `notifications` SET notification = ? WHERE id = ? AND tutor_id = ?");
   $update_notification->execute([$edited_notification, $notification_id, $tutor_id]);

   $message[] = 'Notification edited!';

}elseif(isset($_POST['delete_notification'])){

   $notification_id = $_POST['notification_id'];
   $notification_id = filter_var($notification_id, FILTER_SANITIZE_STRING);

   $delete_notification = $conn->prepare("DELETE FROM `notifications` WHERE id = ? AND tutor_id = ?");
   $delete_notification->execute([$notification_id, $tutor_id]);

   $message[] = 'Notification deleted!';

}elseif(isset($_POST['grade_assignment'])){

   $assignment_id = $_POST['assignment_id'];
   $assignment_id = filter_var($assignment_id, FILTER_SANITIZE_STRING);
   $grade = $_POST['grade'];
   $grade = filter_var($grade, FILTER_SANITIZE_NUMBER_INT);

   $update_grade = $conn->prepare("UPDATE `assignments` SET grade = ? WHERE id = ?");
   $update_grade->execute([$grade, $assignment_id]);

   $message[] = 'Assignment graded!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update content</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css"> 
   <style>
      p{
         font-size: 1.5rem;
      }
   </style>

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="video-form">

   <h1 class="heading">Update video content</h1>

   <?php
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND tutor_id = ?");
      $select_videos->execute([$get_id, $tutor_id]);
      if($select_videos->rowCount() > 0){
         while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
            $video_id = $fecth_videos['id'];
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="video_id" value="<?= $fecth_videos['id']; ?>">
      <input type="hidden" name="old_thumb" value="<?= $fecth_videos['thumb']; ?>">
      <input type="hidden" name="old_video" value="<?= $fecth_videos['video']; ?>">
      <p>Update status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fecth_videos['status']; ?>" selected><?= $fecth_videos['status']; ?></option>
         <option value="active">Active</option>
         <option value="inactive">Inactive</option>
      </select>
      <p>Update title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter video title" class="box" value="<?= $fecth_videos['title']; ?>">
      <p>Update description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write video description" maxlength="1000" cols="30" rows="10"><?= $fecth_videos['description']; ?></textarea>
      <p>Update course</p>
      <select name="playlist" class="box">
         <option value="<?= $fecth_videos['playlist_id']; ?>" selected>--Select course</option>
         <?php
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if($select_playlists->rowCount() > 0){
            while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
            }
         ?>
         <?php
         }else{
            echo '<option value="" disabled>No playlist created yet!</option>';
         }
         ?>
      </select>
      <img src="../uploaded_files/<?= $fecth_videos['thumb']; ?>" alt="">
      <p>Update thumbnail</p>
      <input type="file" name="thumb" accept="image/*" class="box">
      <video src="../uploaded_files/<?= $fecth_videos['video']; ?>" controls></video>
      <p>Update video</p>
      <input type="file" name="video" accept="video/*" class="box">
      <input type="submit" value="Update Video" name="update" class="btn">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Video not found! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">Add Video</a></p>';
      }
   ?>

</section>

<section class="assignment-form">

   <h1 class="heading">Update assignments</h1>

   <?php
      $select_assignments = $conn->prepare("SELECT * FROM `assignments` WHERE id = ? AND tutor_id = ?");
      $select_assignments->execute([$get_id, $tutor_id]);
      if($select_assignments->rowCount() > 0){
         while($fecth_assignments = $select_assignments->fetch(PDO::FETCH_ASSOC)){ 
            $assignment_id = $fecth_assignments['id'];
   ?>
   <form action="" method="post">
      <input type="hidden" name="assignment_id" value="<?= $assignment_id; ?>">
      <p>Update status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fecth_assignments['status']; ?>" selected><?= $fecth_assignments['status']; ?></option>
         <option value="active">Active</option>
         <option value="inactive">Inactive</option>
      </select>
      <p>Update title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter assignment title" class="box" value="<?= $fecth_assignments['title']; ?>">
      <p>Update description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write assignment description" maxlength="1000" cols="30" rows="10"><?= $fecth_assignments['description']; ?></textarea>
      <p>Update due date <span>*</span></p>
      <input type="date" name="due_date" class="box" value="<?= $fecth_assignments['due_date']; ?>">
      <p>Update submission type <span>*</span></p>
      <select name="submission_type" class="box" required>
         <option value="<?= $fecth_assignments['submission_type']; ?>" selected><?= $fecth_assignments['submission_type']; ?></option>
         <option value="online">Online</option>
         <option value="offline">Offline</option>
      </select>
      <p>Grade (out of 25)</p>
      <input type="number" name="grade" min="0" max="25" class="box" value="<?= $fecth_assignments['grade']; ?>">
      <input type="submit" value="Update Assignment" name="update_assignment" class="btn">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">Assignment not found! <a href="add_assignment.php" class="btn" style="margin-top: 1.5rem;">Add Assignment</a></p>';
      }
   ?>

   <h1 class="heading">Grade Assignments</h1>

   <?php
      $select_assignments = $conn->prepare("SELECT * FROM `assignments` WHERE tutor_id = ?");
      $select_assignments->execute([$tutor_id]);
      if($select_assignments->rowCount() > 0){
         while($fetch_assignment = $select_assignments->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post">
      <input type="hidden" name="assignment_id" value="<?= $fetch_assignment['id']; ?>">
      <p>Student Name: <?= $fetch_assignment['student_name']; ?></p>
      <p>Assignment Title: <?= $fetch_assignment['title']; ?></p>
      <p>Assignment Description: <?= $fetch_assignment['description']; ?></p>
      <p>Grade (out of 25)</p>
      <input type="number" name="grade" min="0" max="25" class="box" value="<?= $fetch_assignment['grade']; ?>">
      <input type="submit" value="Grade Assignment" name="grade_assignment" class="btn">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">No assignments to grade!</p>';
      }
   ?>

</section>

<section class="notification-form">

   <h1 class="heading">Manage Notifications</h1>

   <!-- Add Notification Form -->
   <form action="" method="post">
      <p>Add Notification <span>*</span></p>
      <textarea name="notification" class="box" required placeholder="Enter notification" maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="Add Notification" name="add_notification" class="btn">
   </form>

   <!-- Edit Notification Form -->
   <form action="" method="post">
      <p>Edit Notification <span>*</span></p>
      <textarea name="edited_notification" class="box" required placeholder="Enter edited notification" maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="Edit Notification" name="edit_notification" class="btn">
      <p>Select Notification to Edit</p>
      <select name="notification_id" class="box" required>
         <?php
         $select_notifications = $conn->prepare("SELECT * FROM `notifications` WHERE tutor_id = ?");
         $select_notifications->execute([$tutor_id]);
         if($select_notifications->rowCount() > 0){
            while($fetch_notification = $select_notifications->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_notification['id']; ?>"><?= $fetch_notification['notification']; ?></option>
         <?php
            }
         }else{
            echo '<option value="" disabled>No notifications available!</option>';
         }
         ?>
      </select>
   </form>

   <!-- Delete Notification Form -->
   <form action="" method="post">
      <input type="submit" value="Delete Notification" name="delete_notification" class="btn">
      <p>Select Notification to Delete</p>
      <select name="notification_id" class="box" required>
         <?php
         $select_notifications = $conn->prepare("SELECT * FROM `notifications` WHERE tutor_id = ?");
         $select_notifications->execute([$tutor_id]);
         if($select_notifications->rowCount() > 0){
            while($fetch_notification = $select_notifications->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_notification['id']; ?>"><?= $fetch_notification['notification']; ?></option>
         <?php
            }
         }else{
            echo '<option value="" disabled>No notifications available!</option>';
         }
         ?>
      </select>
   </form>

</section>

<?php include '../components/footer.php'; ?>

<script src="../js/admin_script.js"></script>

</body>
</html>
