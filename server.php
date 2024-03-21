<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// User login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            echo "Login successful";
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "Invalid email or password";
    }
}

// Admin actions
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Check if user is admin
    $sql = "SELECT * FROM users WHERE id=$user_id AND is_admin=1";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        // User is admin
        if (isset($_POST['delete_user'])) {
            $user_to_delete = $_POST['user_to_delete'];
            $sql = "DELETE FROM users WHERE id=$user_to_delete";
            if ($conn->query($sql) === TRUE) {
                echo "User deleted successfully";
            } else {
                echo "Error deleting user: " . $conn->error;
            }
        }

        if (isset($_POST['add_product'])) {
            // Code to add product
        }

        if (isset($_POST['delete_product'])) {
            // Code to delete product
        }

        if (isset($_POST['edit_product'])) {
            // Code to edit product
        }
    } else {
        echo "You do not have permission to perform this action";
    }
}

$conn->close();
?>
