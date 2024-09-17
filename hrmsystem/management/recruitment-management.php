<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../includes/DBConnection.php';
$db = new DBConnection();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job_title'];
    $description = $_POST['description'];
    $status = 'open';

    $query = "INSERT INTO recruitments (job_title, description, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $job_title, $description, $status);
    $stmt->execute();
}

$query = "SELECT * FROM recruitments";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recruitment Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        h1 {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            margin: 0;
            text-align: center;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }
        .form-container h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form input[type="text"], form textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }
        form textarea {
            resize: vertical;
            min-height: 100px;
        }
        form input[type="text"]:focus, form textarea:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
        }
        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 1rem;
            color: #333;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h1>Recruitment Management</h1>

    <div class="container">
        <div class="form-container">
            <h2>Add New Job Posting</h2>
            <form action="recruitment-management.php" method="POST">
                <input type="text" name="job_title" placeholder="Job Title" required>
                <textarea name="description" placeholder="Job Description" required></textarea>
                <button type="submit">Add Job</button>
            </form>
        </div>

        <h2>All Job Postings</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Job Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Posted Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['job_title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['posted_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
