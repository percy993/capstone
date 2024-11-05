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

// Add, update, or delete user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $userId = $_POST['userId'];
        
        // Delete the resident but leave the attendance records intact
        $sql = "DELETE FROM residents WHERE id=$userId";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => $conn->error]);
        }
        exit();
    }

    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $zone = $_POST['zone'];
    $phone_number = $_POST['phone_number'];
    $qrCodeUrl = $_POST['qrCodeUrl']; 

    if (isset($_POST['userId']) && $_POST['userId'] != "") {
        // Update existing user
        $userId = $_POST['userId'];
        $sql = "UPDATE residents SET name='$name', age='$age', gender='$gender', zone='$zone', phone_number='$phone_number', qr_code_url='$qrCodeUrl' WHERE id=$userId";
    } else {
        // Insert new user
        $sql = "INSERT INTO residents (name, age, gender, zone, phone_number, qr_code_url) VALUES ('$name', '$age', '$gender', '$zone', '$phone_number', '$qrCodeUrl')";
        
        if ($conn->query($sql) === TRUE) {
            $newUserId = $conn->insert_id; // Get the ID of the newly inserted user
            echo json_encode(["status" => "success", "userId" => $newUserId]);
        } else {
            echo json_encode(["status" => "error", "message" => $conn->error]);
        }
    }
    
    exit();
}

// Fetch users for display in the table
$users = [];
$result = $conn->query("SELECT * FROM residents");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/residents.css">
    
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
               <div class="searh-container">
                    <form class="form">
                      <label for="search">
                         <input class="input" type="text" required="" placeholder="Search residents" id="search">
                         <div class="fancy-bg"></div>
                         <div class="search">
                         <svg viewBox="0 0 24 24" aria-hidden="true" class="r-14j79pv r-4qtqp9 r-yyyyoo r-1xvli5t r-dnmrzs r-4wgw6l r-f727ji r-bnwqim r-1plcrui r-lrvibr">
                             <g>
                                 <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                            </g>
                         </svg>
                         </div>
                             <button class="close-btn" type="reset">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                 </svg>
                             </button>
                      </label>
                     </form>
               </div>
               <div class="notification">
                   <i class='bx bxs-bell' ></i>
               </div>
               <div class="profile">
                    <button class="profile-button">
                        <i class='bx bxs-user-circle'></i> 
                    </button>
                    <div class="dropdown-content">
                        <a href=""><i class='bx bxs-cog'></i> Settings</a>
                        <a href=""> <i class='bx bx-log-out' ></i> Logout</a>
                    </div>
                </div>
       </div>
   </header>
   
   <div class="sidebar">
        <div class="top">
            <div class="logo"><p>Sidebar Menu</p></div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul>
            <li><a href="dashboard.html"><i class='bx bxs-dashboard'></i><span class="nav-item">Dashboard</span></a></li>
            <li><a href="attendance.php"><i class='bx bx-qr-scan'></i><span class="nav-item">Attendance</span></a></li>
            <li><a href="records.php"><i class='bx bxs-folder-open'></i><span class="nav-item">Records</span></a></li>
            <li><a href="#"><i class='bx bx-list-ul'></i><span class="nav-item">Residents</span></a></li>
            <li><a href="#"><i class='bx bxs-archive'></i><span class="nav-item">Archive</span></a></li>
        </ul>
    </div>

    <div class="addtable">
        <div class="table-fixed">
            <h1>Adding Residents with QR Code</h1>
            <form id="userForm">
                <input type="hidden" id="userId" name="userId">
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Zone</th>
                        <th>Phone Number</th>
                        <th>QR Code</th>
                        <th>Actions</th>
                    </tr>
                    <tr>
                        <td><input type="text" id="name" name="name" placeholder="Enter Name" required></td>
                        <td><input type="number" id="age" name="age" placeholder="Enter Age" required></td>
                        <td>
                            <select id="gender" name="gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </td>
                        <td><input type="text" id="zone" name="zone" placeholder="Enter Zone" required></td>
                        <td><input type="text" id="phone_number" name="phone_number" placeholder="Enter Phone Number" required></td>
                        <td>
                            <img id="qrCodeImage" src="https://via.placeholder.com/100" alt="QR Code">
                            <input type="hidden" id="qrCodeUrl" name="qrCodeUrl">
                        </td>
                        <td><button type="submit" id="addBtn">Add Resident</button></td>
                    </tr>
                </table>
            </form>
            
            <hr>
            <table id="userTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Zone</th>
                        <th>Phone Number</th>
                        <th>QR Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr id="row-<?php echo $user['id']; ?>">
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['age']; ?></td>
                        <td><?php echo $user['gender']; ?></td>
                        <td><?php echo $user['zone']; ?></td>
                        <td><?php echo $user['phone_number']; ?></td>
                        <td>
                            <img src="<?php echo $user['qr_code_url']; ?>" alt="QR Code">
                            <a href="<?php echo $user['qr_code_url']; ?>" download="QRCode_<?php echo $user['name']; ?>.png">Download</a>
                        </td>
                        <td class="action-buttons">
                            <button onclick="editUser(<?php echo $user['id']; ?>, '<?php echo $user['name']; ?>', <?php echo $user['age']; ?>, '<?php echo $user['gender']; ?>', '<?php echo $user['zone']; ?>', '<?php echo $user['phone_number']; ?>', '<?php echo $user['qr_code_url']; ?>')">Edit</button>
                            <button class="delete" onclick="deleteUser(<?php echo $user['id']; ?>)">Archive</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let btn = document.querySelector('#btn');
        let sidebar = document.querySelector('.sidebar');

        btn.onclick = function () {
            sidebar.classList.toggle('active');
        };

        function generateQRCode() {
            const name = document.getElementById("name").value;
            const zone = document.getElementById("zone").value;
            const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=${encodeURIComponent(`Name: ${name}, Zone: ${zone}`)}`;
            document.getElementById("qrCodeImage").src = qrCodeUrl;
            document.getElementById("qrCodeUrl").value = qrCodeUrl;
        }

        document.getElementById("name").addEventListener("input", generateQRCode);
        document.getElementById("zone").addEventListener("input", generateQRCode);

        document.getElementById("addBtn").addEventListener("click", function (e) {
            e.preventDefault(); // Prevent default form submission
            const formData = new FormData(document.getElementById("userForm"));

            // Check if required fields are filled
            if (!formData.get('name') || !formData.get('age') || !formData.get('gender') || !formData.get('zone')) {
                alert("Please fill in all required fields.");
                return;
            }

            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.status === "success") {
                      const newUser = {
                          id: data.userId || document.getElementById('userId').value, // Use returned userId or existing userId for updates
                          name: document.getElementById("name").value,
                          age: document.getElementById("age").value,
                          gender: document.getElementById("gender").value,
                          zone: document.getElementById("zone").value,
                          phone_number: document.getElementById("phone_number").value,
                          qr_code_url: document.getElementById("qrCodeUrl").value
                      };

                      // Check if we're editing an existing user
                      if (document.getElementById('userId').value) {
                          const row = document.getElementById(`row-${newUser.id}`);
                          row.innerHTML = `
                              <td>${newUser.name}</td>
                              <td>${newUser.age}</td>
                              <td>${newUser.gender}</td>
                              <td>${newUser.zone}</td>
                              <td>${newUser.phone_number}</td>
                              <td>
                                  <img src="${newUser.qr_code_url}" alt="QR Code">
                                  <a href="${newUser.qr_code_url}" download="QRCode_${newUser.name}.png">Download</a>
                              </td>
                              <td class="action-buttons">
                                  <button onclick="editUser(${newUser.id}, '${newUser.name}', ${newUser.age}, '${newUser.gender}', '${newUser.zone}', '${newUser.phone_number}', '${newUser.qr_code_url}')">Edit</button>
                                  <button class="delete" onclick="deleteUser(${newUser.id})">Delete</button>
                              </td>
                          `;
                      } else {
                          // Add new resident to the table dynamically
                          const newRow = document.createElement("tr");
                          newRow.id = `row-${newUser.id}`;
                          newRow.innerHTML = `
                              <td>${newUser.name}</td>
                              <td>${newUser.age}</td>
                              <td>${newUser.gender}</td>
                              <td>${newUser.zone}</td>
                               <td>${newUser.phone_number}</td>
                              <td>
                                  <img src="${newUser.qr_code_url}" alt="QR Code">
                                  <a href="${newUser.qr_code_url}" download="QRCode_${newUser.name}.png">Download</a>
                              </td>
                              <td class="action-buttons">
                                  <button onclick="editUser(${newUser.id}, '${newUser.name}', ${newUser.age}, '${newUser.gender}', '${newUser.zone}', '${newUser.phone_number}', '${newUser.qr_code_url}')">Edit</button>
                                  <button class="delete" onclick="deleteUser(${newUser.id})">Delete</button>
                              </td>
                          `;

                          document.querySelector("#userTable tbody").appendChild(newRow);
                      }

                      // Clear the form fields
                      document.getElementById("userForm").reset();
                      document.getElementById("qrCodeImage").src = "https://via.placeholder.com/100"; // Reset QR Code image
                  } else {
                      alert("Error: " + data.message);
                  }
              });
        });

        function editUser(id, name, age, gender, zone, phone_number, qrCodeUrl) {
            document.getElementById('userId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('age').value = age;
            document.getElementById('gender').value = gender;
            document.getElementById('zone').value = zone;
            document.getElementById('phone_number').value = phone_number;
            document.getElementById('qrCodeImage').src = qrCodeUrl;
            document.getElementById('qrCodeUrl').value = qrCodeUrl;
        }

        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this resident?")) {
                const formData = new FormData();
                formData.append('userId', userId);
                formData.append('action', 'delete');
                fetch('', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                  .then(data => {
                      if (data.status === "success") {
                          // Remove the row from the table dynamically
                          document.getElementById('row-' + userId).remove();
                      } else {
                          alert("Error: " + data.message);
                      }
                  });
            }
        }
    </script>
</body>
</html>
