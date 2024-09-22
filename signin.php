<?php
session_start();
include 'db.php'; // Include database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: main.html"); // Redirect to the main page after successful login
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href = 'index.html';</script>";
        }
    } else {
        echo "<script>alert('No account found with this email!'); window.location.href = 'index.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
