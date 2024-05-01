<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

if(isset($_POST['submit'])){

   // Code to add content (videos)
   $id = unique_id();
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $playlist = $_POST['playlist'];
   $playlist = filter_var($playlist, FILTER_SANITIZE_STRING);

   $thumb = $_FILES['thumb']['name'];
   $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded_files/'.$rename_thumb;

   $video = $_FILES['video']['name'];
   $video = filter_var($video, FILTER_SANITIZE_STRING);
   $video_ext = pathinfo($video, PATHINFO_EXTENSION);
   $rename_video = unique_id().'.'.$video_ext;
   $video_tmp_name = $_FILES['video']['tmp_name'];
   $video_folder = '../uploaded_files/'.$rename_video;

   if($thumb_size > 2000000){
      $message[] = 'image size is too large!';
   }else{
      $add_playlist = $conn->prepare("INSERT INTO `content`(id, tutor_id, playlist_id, title, description, video, thumb, status) VALUES(?,?,?,?,?,?,?,?)");
      $add_playlist->execute([$id, $tutor_id, $playlist, $title, $description, $rename_video, $rename_thumb, $status]);
      move_uploaded_file($thumb_tmp_name, $thumb_folder);
      move_uploaded_file($video_tmp_name, $video_folder);
      $message[] = 'new course uploaded!';
   }
   
}elseif(isset($_POST['create_assignment'])){

   // Code to create assignments (experiments)
   $id = unique_id();
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   // Add assignment to the database
   $add_assignment = $conn->prepare("INSERT INTO `assignments`(id, tutor_id, title, description, status) VALUES(?,?,?,?,?)");
   $add_assignment->execute([$id, $tutor_id, $title, $description, $status]);
   $message[] = 'New assignment created!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="video-form">

   <!-- Video upload form -->

   <h1 class="heading">Upload content</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <p>Video status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- Select status</option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>
      <p>Video title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="enter video title" class="box">
      <p>Video description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>Video course <span>*</span></p>
      <select name="playlist" class="box" required>
         <option value="" disabled selected>--Select course</option>
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
            echo '<option value="" disabled>no course created yet!</option>';
         }
         ?>
      </select>
      <p>Select thumbnail <span>*</span></p>
      <input type="file" name="thumb" accept="image/*" required class="box">
      <p>Select video <span>*</span></p>
      <input type="file" name="video" accept="video/*" required class="box">
      <input type="submit" value="upload video" name="submit" class="btn">
   </form>

   <!-- Video upload form -->

   <!-- Assignment creation form -->

   <h1 class="heading">Create Assignment</h1>

   <form action="" method="post">
      <p>Assignment status <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- Select status</option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>
      <p>Assignment title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Enter assignment title" class="box">
      <p>Assignment description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Write assignment description" maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="Create Assignment" name="create_assignment" class="btn">
   </form>

   <!-- Assignment creation form -->

</section>

<!-- Other sections -->

<?php include '../components/footer.php'; ?>

<!-- Scripts -->

</body>
</html>
