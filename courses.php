<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle course addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $course_name = $_POST['course_name'];
    $stmt = $conn->prepare("INSERT INTO courses (user_id, course_name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $course_name);
    $stmt->execute();
    $stmt->close();
}

// Handle course deletion
if (isset($_POST['delete'])) {
    $course_id = $_POST['course_id'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $course_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Handle course editing
if (isset($_POST['edit'])) {
    $course_id = $_POST['course_id'];
    $new_course_name = $_POST['new_course_name'];
    $stmt = $conn->prepare("UPDATE courses SET course_name = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $new_course_name, $course_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch courses for the logged-in user
$stmt = $conn->prepare("SELECT * FROM courses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$courses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Your Courses</title>
</head>
<body>
    <h2>Your Courses</h2>
    
    <form method="POST">
        <input type="text" name="course_name" placeholder="Course Name" required>
        <button type="submit" name="add">Add Course</button>
    </form>

    <table>
        <tr>
            <th>Course Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($courses as $course): ?>
        <tr>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="text" name="new_course_name" value="<?php echo $course['course_name']; ?>" required>
                    <button type="submit" name="edit">Edit</button>
                </form>
            </td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
