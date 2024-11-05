<?php
// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "bdams_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch attendance records
$sql = "SELECT resident_name, resident_zone, DATE_FORMAT(time_in, '%m-%d-%Y %r') as time_in, 
        DATE_FORMAT(time_out, '%m-%d-%Y %r') as time_out 
        FROM attendance 
        ORDER BY time_in DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $attendance = []; // Initialize attendance array
    while ($row = $result->fetch_assoc()) {
        $attendance[] = [
            'name' => $row['resident_name'], 
            'zone' => $row['resident_zone'], 
            'time_in' => $row['time_in'], 
            'time_out' => $row['time_out']
        ];
    }
} else {
    $attendance = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/records.css">
</head>
<body>
    <header class="dashboard-header">
        <div class="grid-header">
            <div>
                <img src="img/logo.jpg" class="logo-header" alt="">
            </div>
            <div class="BDAMS">
                <p>Barangay Dalla Attendance Monitoring System</p>
            </div>
            <div class="notification">
                <i class='bx bxs-bell'></i>
            </div>
            <div class="profile">
                <button class="profile-button">
                    <i class='bx bxs-user-circle'></i>
                </button>
                <div class="dropdown-content">
                    <a href=""><i class='bx bxs-cog'></i> Settings</a>
                    <a href=""><i class='bx bx-log-out'></i> Logout</a>
                </div>
            </div>     
        </div>           
    </header>
    <div class="sidebar">
        <div class="top">
            <div class="logo">
                <p>Sidebar Menu</p>
            </div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul>
            <li>
                <a href="dashboard.html">
                    <i class='bx bxs-dashboard'></i>
                    <span class="nav-item">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="attendance.php">
                    <i class='bx bx-qr-scan'></i>
                    <span class="nav-item">Attendance</span>
                </a>
            </li>
            <li>
                <a href="records.php">
                    <i class='bx bxs-folder-open'></i>
                    <span class="nav-item">Records</span>
                </a>
            </li>
            <li>
                <a href="residents.php">
                    <i class='bx bx-list-ul'></i>
                    <span class="nav-item">Residents</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Attendance Records</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Zone</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['name']) ?></td>
                        <td><?= htmlspecialchars($record['zone']) ?></td>
                        <td><?= htmlspecialchars($record['time_in']) ?></td>
                        <td><?= htmlspecialchars($record['time_out']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        let btn = document.querySelector('#btn');
        let sidebar = document.querySelector('.sidebar');

        btn.onclick = function() {
            sidebar.classList.toggle('active');
        };
    </script>
</body>
</html>
