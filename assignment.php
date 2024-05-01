<?php
session_start(); // Start the session

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for both teachers and students
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve assignment data from form
    $title = $_POST['assignmentTitle'];
    $type = $_POST['submissionType'];
    $date = isset($_POST['dueDate']) ? $_POST['dueDate'] : null; // Check if dueDate is set
    $course = $_POST['courseSelect']; // Retrieve course data
    $exp_no = $_POST['expNoSelect']; // Retrieve Exp. No. data

    // File upload handling
    $file_name = $_FILES['file']['name'];
    $file_temp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];

    // Check if file is uploaded successfully
    if ($file_name != "") {
        // File upload directory
        $target_dir = "uploads/";
        // Generate unique file name to prevent overwriting
        $target_file = $target_dir . basename($file_name);
        // Move uploaded file to destination
        if (move_uploaded_file($file_temp, $target_file)) {
            echo "File uploaded successfully.";
        } else {
            echo "Error uploading file.";
        }
    }

    // Implement your database connection and insertion logic here
    $servername = "localhost";
    $username = "root"; // MySQL username
    $password = ""; // Empty password
    $dbname = "course_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Read file data
    $file_data = file_get_contents($file_temp);

    // Prepare SQL statement and execute
    $sql = "INSERT INTO assignment (title, type, due_date, course, exp_no, file_name, file_data) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $title, $type, $date, $course, $exp_no, $file_name, $file_data);
    if ($stmt->execute()) {
        echo "Assignment submitted successfully";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* CSS styles to center the form */
        .center-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid black;
            padding: 30px;
            width: 50%;
            box-sizing: border-box;
        }

        /* Additional styles for form elements */
        form div {
            margin-bottom: 15px; /* Add space between form elements */
        }

        label {
            display: block; /* Make labels appear on their own line */
        }

        input[type="text"],
        input[type="datetime-local"],
        select,
        input[type="file"] {
            width: 100%; /* Make form fields fill the entire width */
            padding: 5px;
            margin-top: 5px;
            box-sizing: border-box; /* Include padding and border in the width */
        }

        button[type="submit"] {
            width: 100%; /* Make submit button fill the entire width */
            padding: 10px;
            margin-top: 10px;
            box-sizing: border-box; /* Include padding and border in the width */
            background-color: darkred;
            color: white;
            border: none;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: darkred; /* Darken the background color on hover */
        }

        h1{
            text-align: center;
            padding-top: 30px;
            padding-right: 300px;
            font-size: 40px;
        }
    </style>
</head>
<body>
    <h1>Assignment</h1>

    <form method="post" enctype="multipart/form-data" class="center-form">
    <div>
        <label for="courseSelect">Select Course:</label>
        <select id="courseSelect" name="courseSelect" required>
            <option value="Learning Web Development">Learning Web Development</option>
            <option value="AOA Lab">AOA Lab</option>
            <option value="RDBMS Lab">RDBMS Lab</option>
            <!-- Add more options as needed -->
        </select>
    </div>
    <div>
        <label for="expNoSelect">Select Exp. No.:</label>
        <select id="expNoSelect" name="expNoSelect" required>
            <option value="1">1.</option>
            <option value="2">2.</option>
            <option value="3">3.</option>
            <!-- Add more options as needed -->
        </select>
    </div>
    <div>
        <label for="assignmentTitle">Title:</label>
        <input type="text" id="assignmentTitle" name="assignmentTitle" required>
    </div>
    <div>
        <label for="submissionType">Submission Type:</label>
        <select id="submissionType" name="submissionType" required>
            <option value="document">Document</option>
            <option value="pdf">PDF</option>
        </select>
    </div>
    <div>
        <label for="dueDate"> Date:</label>
        <input type="datetime-local" id="dueDate" name="dueDate" required>
    </div>
    <div>
        <label for="file">File:</label>
        <input type="file" id="file" name="file" required>
    </div>
    <button type="submit">Submit</button>
</form>
</body>
</html>
