<?php
require_once('process/dbh.php');

// Check if employee deletion is requested
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // Fetch employee details before deletion
    $fetch_query = "SELECT * FROM `employee` WHERE id = $delete_id";
    $fetch_result = mysqli_query($conn, $fetch_query);
    $employee = mysqli_fetch_assoc($fetch_result);

    // Insert employee details into history table
    $history_query = "INSERT INTO `history` (emp_id, first_name, last_name, email, deleted_at)
                      VALUES ('{$employee['id']}', '{$employee['firstName']}', '{$employee['lastName']}', '{$employee['email']}', NOW())";
    mysqli_query($conn, $history_query);

    // Delete employee from employee table
    $delete_query = "DELETE FROM `employee` WHERE id = $delete_id";
    mysqli_query($conn, $delete_query);
}

// Fetch employee details from employee table
$sql = "SELECT * FROM `employee`";
$result = mysqli_query($conn, $sql);
?>

<!-- HTML code for displaying employee details -->
<html>
<head>
    <title>View Employee | Admin Panel | TeamSync</title>
    <link rel="stylesheet" type="text/css" href="styleview.css">
</head>
<body>
<header>
    <!-- Navigation bar -->
    <!-- Your existing navigation code goes here -->
</header>
<div class="divider"></div>

<!-- Table to display employee details -->
<table>
    <tr>
        <th align="center">Emp. ID</th>
        <!-- Add more table headers as needed -->
        <th align="center">Actions</th>
    </tr>
    <?php
    // Loop through fetched employee data and display it in the table
    while ($employee = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$employee['id']."</td>";
        // Add more table cells with employee details as needed
        echo "<td><a href=\"edit.php?id={$employee['id']}\">Edit</a> | <a href=\"viewemp.php?delete_id={$employee['id']}\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>