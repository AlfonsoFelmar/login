<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "user_info";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete user if the delete request is received
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM user_database WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('User deleted successfully!'); window.location.href = 'userlist.php';</script>";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}

// Update user info if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $contactnumber = mysqli_real_escape_string($conn, $_POST['contactnumber']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Update the user's information
    $sql = "UPDATE user_database SET firstname='$firstname', lastname='$lastname', username='$username', 
            contactnumber='$contactnumber', email='$email' WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('User updated successfully!'); window.location.href = 'userlist.php';</script>";
    } else {
        echo "Error updating user: " . $conn->error;
    }
}

// Fetch all users from the database
$sql = "SELECT * FROM user_database";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/userlist.css">
</head>
<body>

<div class="container">
    <h1>Registered Users</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Contact Number</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['firstname']}</td>
                        <td>{$row['lastname']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['contactnumber']}</td>
                        <td>{$row['email']}</td>
                        <td>
                            <button class='edit-btn' onclick='editUser(" . json_encode($row) . ")'>Edit</button>
                            <form method='post' action='userlist.php' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <input type='submit' name='delete_user' value='Delete' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this user?\")'>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No users found</td></tr>";
        }
        ?>
    </table>
</div>

<!-- Modal for editing user -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">X</button>
        <h2>Edit User</h2>
        <form class="update-form" method="post" action="userlist.php">
            <input type="hidden" name="id" id="userId">
            <label for="userFirstname">First Name:</label>
            <input type="text" name="firstname" id="userFirstname" placeholder="First Name" required>
            <label for="userLastname">Last Name:</label>
            <input type="text" name="lastname" id="userLastname" placeholder="Last Name" required>
            <label for="userUsername">Username:</label>
            <input type="text" name="username" id="userUsername" placeholder="Username" required>
            <label for="userContact">Contact Number:</label>
            <input type="tel" name="contactnumber" id="userContact" placeholder="Contact Number" pattern="[0-9]{10}" required>
            <label for="userEmail">Email:</label>
            <input type="email" name="email" id="userEmail" placeholder="Email" required>
            <input type="submit" name="update_user" value="Update User">
        </form>
    </div>
</div>

<script>
// JavaScript to open the modal and populate the form
function editUser(user) {
    document.getElementById("editModal").style.display = "flex";
    document.getElementById("userId").value = user.id;
    document.getElementById("userFirstname").value = user.firstname;
    document.getElementById("userLastname").value = user.lastname;
    document.getElementById("userUsername").value = user.username;
    document.getElementById("userContact").value = user.contactnumber;
    document.getElementById("userEmail").value = user.email;
}

// JavaScript to close the modal
function closeModal() {
    document.getElementById("editModal").style.display = "none";
}
</script>

</body>
</html>

<?php
$conn->close();
?>