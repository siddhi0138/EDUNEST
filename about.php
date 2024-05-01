<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      p, h3{
         font-size: 1.5rem;
      }
   </style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
         <h3>Why Edunest?</h3>
         <p>EDUNEST offers a top-notch learning management system renowned for its excellence in engineering education.</p>
         <a href="courses.php" class="inline-btn">Our Courses</a>
      </div>

   </div>

   <div class="box-container">

      <div class="box">
         <i class="fas fa-graduation-cap"></i>
         <div>
            <h3>+10k</h3>
            <p>Alumni</p>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-user-graduate"></i>
         <div>
            <h3>200+</h3>
            <p>Teachers</p>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-briefcase"></i>
         <div>
            <p>KJSCE has been Awarded 'A' Grade by The (NAAC) From May 2017</p>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-briefcase"></i>
         <div>
            <p> NIRF 2020: Rank 171 Ranked by MHRD</p>
         </div>
      </div>

      <div class="box">
         <i class="fas fa-user-graduate"></i>
         <div>
            
            <p>Gold Status in CII Survey 2017</p>
         </div>
      </div>



      <div class="box">
         <i class="fas fa-chalkboard-user"></i>
         <div>
            <p>Highest package </p>
            <p>58 LPA</p>
            <p>Average Package </p>
            <p>10+ LPA</p>
         </div>
      </div>

      <div class="box">s
         <i class="fas fa-briefcase"></i>
         <div>
            <h3>100%</h3>
            <p>job placement</p>
         </div>
      </div>

   </div>

</section> 


<!-- about section ends -->

<!-- reviews section starts  -->

<section class="reviews">

   <h1 class="heading">Student's Reviews</h1>

   <div class="box-container">

      <div class="box">
         <p>Edunest has revolutionized the way I learn! With its user-friendly interface and intuitive design, accessing course materials and submitting assignments has never been easier.</p>
         <div class="user">
            <img src="images/pic-2.jpg" alt="">
            <div>
               <h3>Mayank Thakur</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>I love how Edunest allows me to connect with my classmates and professors from anywhere in the world. It's like having a virtual classroom right at my fingertips!</p>
         <div class="user">
            <img src="images/pic-3.jpg" alt="">
            <div>
               <h3>Pranav Kumar</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>The variety of multimedia resources available on Edunest makes studying engaging and dynamic. From interactive quizzes to video lectures, there's always something new to explore.</p>
         <div class="user">
            <img src="images/pic-4.jpg" alt="">
            <div>
               <h3>Piyush Gupta</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>The discussion forums on Edunest have fostered a sense of community among students. It's great to be able to share ideas and collaborate with my peers outside of the traditional classroom setting.</p>
         <div class="user">
            <img src="images/pic-5.jpg" alt="">
            <div>
               <h3>Aman Agarwal</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>Overall, Edunest has exceeded my expectations as an online learning platform. It has truly transformed the way I approach education and has equipped me with the skills I need to succeed in the digital age.</p>
         <div class="user">
            <img src="images/pic-6.jpg" alt="">
            <div>
               <h3>Namita Goyal</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>As a visual learner, I appreciate how Edunest incorporates multimedia elements into its lessons. The interactive diagrams and animations really help bring complex concepts to life.</p>
         <div class="user">
            <img src="images/pic-7.jpg" alt="">
            <div>
               <h3>Omkar Shetty</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

   </div>

</section>

<!-- reviews section ends -->










<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>