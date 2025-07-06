<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
// if(!isset($_SESSION['system'])){
  $system = $conn->query("SELECT * FROM system_settings")->fetch_array();
  foreach($system as $k => $v){
    $_SESSION['system'][$k] = $v;
  }
// }
ob_end_flush();
?>
<?php 
if(isset($_SESSION['login_id']))
  header("location:index.php?page=home");
?>
<?php include 'header.php' ?>
<body class="hold-transition login-page bg-">
  <div class="login-box">
    <div class="card">
      <div class="card-body login-card-body text-center">
        <!-- Logo and Title Inside the Login Form -->
        <img src="assets/uploads/logo.jpeg" alt="Logo" style="width: 90px; height: 90px; border-radius: 60%;">
        <h6 class="mt-1 mb-3"><b>Mentor Link: Digital Academic Guidance System</b></h6>

        <form action="" id="login-form">
          <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" required placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" required placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="form-group mb-3 text-left">
            <label for="">Login As</label>
            <select name="login" id="" class="custom-select custom-select-sm">
              <option value="3">Student</option>
              <option value="2">Faculty</option>
              <option value="1">Admin</option>
            </select>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary text-left">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->
  <script>
    $(document).ready(function(){
      $('#login-form').submit(function(e){
        e.preventDefault()
        start_load()
        if($(this).find('.alert-danger').length > 0 )
          $(this).find('.alert-danger').remove();
        $.ajax({
          url:'ajax.php?action=login',
          method:'POST',
          data:$(this).serialize(),
          error:err=>{
            console.log(err)
            end_load();
          },
          success:function(resp){
            if(resp == 1){
              location.href ='index.php?page=home';
            }else{
              $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
              end_load();
            }
          }
        })
      })
    })
  </script>
<?php include 'footer.php' ?>
</body>
</html>

