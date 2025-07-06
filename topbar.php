<style>
  .user-img {
    border-radius: 50%;
    height: 25px;
    width: 25px;
    object-fit: cover;
  }

  .nav-link .badge {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 10px;
  }

  /* Improved Dropdown Design */
  .dropdown-menu {
    border-radius: 10px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    padding: 0;
  }

  .dropdown-item {
    padding: 10px 15px;
    font-size: 14px;
    transition: background-color 0.3s ease;
  }

  .dropdown-item:hover {
    background-color: #f0f0f0;
    cursor: pointer;
  }

  .dropdown-header {
    background-color: #007bff;
    color: #fff;
    font-weight: bold;
    padding: 10px 15px;
  }

  .dropdown-divider {
    border-color: #e0e0e0;
  }

  /* Message & Notification Card Design */
  .dropdown-item .media {
    display: flex;
    align-items: center;
  }

  .dropdown-item .media img {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    object-fit: cover;
    margin-right: 15px;
  }

  .dropdown-item .media-body {
    flex-grow: 1;
  }

  .dropdown-item .media-body h3 {
    font-size: 14px;
    margin-bottom: 5px;
    font-weight: bold;
  }

  .dropdown-item .media-body p {
    font-size: 12px;
    color: #6c757d;
  }

  .text-muted {
    font-size: 12px;
  }

  .dropdown-footer {
    text-align: center;
    padding: 10px;
    background-color: #f8f9fa;
    color: #007bff;
    font-weight: bold;
  }

  .dropdown-footer:hover {
    background-color: #e9ecef;
    cursor: pointer;
  }

  .badge-success {
    background-color: #28a745;
  }

  .badge-warning {
    background-color: #ffc107;
  }

  /* Enhanced Bell and Message Icon */
  .nav-link .badge {
    border-radius: 50%;
    padding: 5px 8px;
    font-size: 12px;
  }

  .nav-link i {
    font-size: 18px;
    transition: transform 0.3s ease;
  }

  .nav-link:hover i {
    transform: scale(1.1);
  }
</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-primary navbar-dark">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <?php if(isset($_SESSION['login_id'])): ?>
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    <?php endif; ?>
    <li>
      <a class="nav-link text-white" href="./" role="button">
        <large><b>Mentor Link: Digital Academic Guidance System</b></large>
      </a>
    </li>
  </ul>

  <!-- Search Bar -->
  <form class="form-inline ml-3">
    <div class="input-group input-group-sm">
      <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-navbar" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-envelope"></i>
        <span class="badge badge-success navbar-badge">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">3 New Messages</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <div class="media">
            <img src="assets/uploads/default_avatar.png" alt="User Avatar" class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                John Doe
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">Can we schedule a consultation?</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li>

    <!-- Notifications Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">5</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">5 Notifications</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-users mr-2"></i> 3 new students joined
          <span class="float-right text-muted text-sm">2 mins</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li>

    <!-- Fullscreen Button -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    <!-- User Account -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" aria-expanded="true" href="javascript:void(0)">
        <span>
          <div class="d-flex align-items-center">
            <img src="assets/uploads/<?php echo $_SESSION['login_avatar'] ?>" alt="" class="user-img border mr-2">
            <b><?php echo ucwords($_SESSION['login_firstname']) ?></b>
            <span class="fa fa-angle-down ml-2"></span>
          </div>
        </span>
      </a>
      <div class="dropdown-menu" aria-labelledby="account_settings" style="left: -2.5em;">
        <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage Account</a>
        <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
      </div>
    </li>
  </ul>
</nav>

<script>
  $('#manage_account').click(function(){
    uni_modal('Manage Account','manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
  });
</script>
