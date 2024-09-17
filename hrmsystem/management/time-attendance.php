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
    $employee_id = $_POST['employee_id'];
    $date = $_POST['date'];
    $clock_in = $_POST['clock_in'];
    $clock_out = $_POST['clock_out'];

    $query = "INSERT INTO attendance (employee_id, date, clock_in, clock_out) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isss', $employee_id, $date, $clock_in, $clock_out);
    $stmt->execute();
}

$query = "SELECT * FROM attendance";
$result = $conn->query($query);

$query2 = "SELECT id, name FROM employees";
$employees = $conn->query($query2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Time and Attendance</title>
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
        form select, form input[type="date"], form input[type="time"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }
        form select:focus, form input:focus {
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
    <h1>Time and Attendance</h1>

    <div class="container">
        <div class="form-container">
            <h2>Record Attendance</h2>
            <form action="time-attendance.php" method="POST">
                <select name="employee_id" required>
                    <?php while ($row = $employees->fetch_assoc()) : ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="date" name="date" required>
                <input type="time" name="clock_in" placeholder="Clock In" required>
                <input type="time" name="clock_out" placeholder="Clock Out" required>
                <button type="submit">Submit Attendance</button>
            </form>
        </div>

        <h2>Attendance Records</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Employee ID</th>
                <th>Date</th>
                <th>Clock In</th>
                <th>Clock Out</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['employee_id']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['clock_in']; ?></td>
                    <td><?php echo $row['clock_out']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
