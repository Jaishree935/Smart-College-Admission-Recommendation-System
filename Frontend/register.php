<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (!empty($username) && !empty($email) && !empty($password)) {
            $host = "localhost";
            $dbusername = "root";
            $dbpassword = "";
            $dbname = "userdetail";

            $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Check if email already exists
            $SELECT = "SELECT email FROM users WHERE email = ? LIMIT 1";
            $INSERT = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

            $stmt = $conn->prepare($SELECT);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;
            $stmt->close();

            if ($rnum == 0) {
                // ✅ Hash the password before storing it
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $conn->prepare($INSERT);
                $stmt->bind_param("sss", $username, $email, $hashedPassword);
                if ($stmt->execute()) {
                    header("Location: register.html?success=1"); // Redirect with success message
                    exit();
                } else {
                    header("Location: register.html?error=database"); // Redirect with error message
                    exit();
                }
                $stmt->close();
            } else {
                echo "Email already registered!";
            }
            $conn->close();
        } else {
            echo "All fields are required.";
        }
    } else {
        echo "Invalid form submission.";
    }
} else {
    echo "Invalid request method.";
}
?>
