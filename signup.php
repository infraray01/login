<?php
session_start();
include 'db.php'; // Include database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email is already registered
    $check_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already taken'); window.location.href = 'index.html';</script>";
    } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href = 'index.html';</script>";
        } else {
            echo "<script>alert('Error registering user.'); window.location.href = 'index.html';</script>";
        }

        $stmt->close();
    }
    $check_email->close();
    $conn->close();
}
?>
