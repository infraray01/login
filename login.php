// login.php
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'mywebsite');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            if ($row['is_verified'] == 1) {
                $_SESSION['user_id'] = $row['id'];
                echo "Login successful!";
                // Redirect to a logged-in page
            } else {
                echo "Please verify your email first.";
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }
}

$conn->close();
?>
