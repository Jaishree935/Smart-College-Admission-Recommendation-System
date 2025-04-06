<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "userdetail";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];
$age = $_POST['age'];
$gender = $_POST['gender'];

// Image Upload Handling
$profile_pic = "uploads/default.png"; // Default profile picture
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    
    $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
    move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
    $profile_pic = $target_file;
}

// Check if Email Already Exists
$checkEmail = $conn->prepare("SELECT email FROM users_data WHERE email = ?");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$checkEmail->store_result();

if ($checkEmail->num_rows > 0) {
    // Update existing user
    $sql = "UPDATE users_data SET name=?, phone=?, dob=?, age=?, gender=?, profile_pic=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiss", $name, $phone, $dob, $age, $gender, $profile_pic, $email);
} else {
    // Insert new user
    $sql = "INSERT INTO users_data (name, email, phone, dob, age, gender, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiss", $name, $email, $phone, $dob, $age, $gender, $profile_pic);
}

// Execute Query
if ($stmt->execute()) {
    header("Location: profile.html?success=Profile updated!");
} else {
    header("Location: profile.html?error=Database error!");
}

$stmt->close();
$conn->close();
?>
