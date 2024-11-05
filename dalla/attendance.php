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

// Log attendance when QR code is scanned
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $qrCodeData = $_POST['qrCodeData'];

    // Extract name and zone from the QR code
    preg_match('/Name:\s*(.*),\s*Zone:\s*(.*)/', $qrCodeData, $matches);
    if (count($matches) < 3) {
        echo json_encode(["status" => "error", "message" => "Invalid QR code data"]);
        exit();
    }

    $name = $matches[1];
    $zone = $matches[2];

    // Find the resident in the database by their name and zone
    $sql = "SELECT id FROM residents WHERE name=? AND zone=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $zone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Resident found
        $resident = $result->fetch_assoc();
        $resident_id = $resident['id'];

        // Check if there's already an attendance record for today
        $today = date('Y-m-d');
        $sql = "SELECT * FROM attendance WHERE resident_id=? AND DATE(scan_date)=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $resident_id, $today);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // No record for today, insert as time in
            $sql = "INSERT INTO attendance (resident_id, resident_name, resident_zone, time_in, scan_date) 
                    VALUES (?, ?, ?, NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $resident_id, $name, $zone);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Time in recorded successfully sheesh"]);
            } else {
                echo json_encode(["status" => "error", "message" => $stmt->error]);
            }
        } elseif ($result->num_rows == 1) {
            // One record for today, update as time out
            $attendance = $result->fetch_assoc();
            if (is_null($attendance['time_out'])) {
                $sql = "UPDATE attendance SET time_out=NOW() WHERE resident_id=? AND DATE(scan_date)=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $resident_id, $today);
                if ($stmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Time out recorded successfully sheesh"]);
                } else {
                    echo json_encode(["status" => "error", "message" => $stmt->error]);
                }
            } else {
                // Already has time out
                echo json_encode(["status" => "error", "message" => "You already scanned the QR code for today sheesh"]);
            }
        }
    } else {
        // Resident not found
        echo json_encode(["status" => "error", "message" => "Resident not found"]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/attendance.css">
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <style>
        #message {
            display: none;
            color: #080808;
            font-weight: bold;
            margin-top: 30px;
            background-color: #7cfc00;
        
            
        }
    </style>
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
                <a href="#">
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
        <h1>QR Code Scanner</h1>
        <video id="preview" style="width: 100%; height: 400px;"></video>
        <div id="message">SUCCESSFULLY SCANNED!</div>
    </div>

    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

        // Function to show success message briefly
        function showMessage(text) {
            const message = document.getElementById('message');
            message.textContent = text;
            message.style.display = 'block';
            setTimeout(() => {
                message.style.display = 'none';
            }, 1500); // Message disappears after 1.5 seconds
        }

        // Listener for the scanned content
        scanner.addListener('scan', function (content) {
            fetch('attendance.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ qrCodeData: content })
            }).then(response => response.json())
              .then(data => {
                  if (data.status === 'success') {
                      showMessage(data.message);
                  } else {
                      showMessage('Error: ' + data.message);
                  }
              });
        });

        // Start the camera and scanner
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });

        let btn = document.querySelector('#btn');
        let sidebar = document.querySelector('.sidebar');

        btn.onclick = function() {
            sidebar.classList.toggle('active');
        };
    </script>
</body>
</html>
