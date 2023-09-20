<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="style/bootstrap.min.css" rel="stylesheet">
    <link href="style/login.css" rel="stylesheet">
    <meta name="theme-color" content="#1995f0">
  </head>
  <body>

	<div class="container-fluid ps-md-0">
	  <div class="row g-0">
	    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
	    <div class="col-md-8 col-lg-6">
	      <div class="login d-flex align-items-center py-5">
	        <div class="container">
	          <div class="row">
	            <div class="col-md-9 col-lg-8 mx-auto">

							<!-- Check if the error GET parameter is set -->
							<?php if (isset($_GET['error'])): ?>
							  <!-- Show the error callout based on the error type -->
							  <?php switch ($_GET['error']): 
							    case 'invalid_email': ?>
							      <div class="alert alert-danger">
							        Invalid email format. Please try again.
							      </div>
							      <?php break;
							    case 'invalid_data': ?>
							      <div class="alert alert-danger">
							        Login failed. Please try again.
							      </div>
							      <?php break;
							    case 'user_agent_changed': ?>
							      <div class="alert alert-danger">
							        You have been logged out; please log in again.
							      </div>
							      <?php break;	
							    case 'invalid_token': ?>
							      <div class="alert alert-warning">
							        Log in to continue
							      </div>
							      <?php break;		
							    case 'user_disabled': ?>
							      <div class="alert alert-danger">
							        User has been deactivated
							      </div>
							      <?php break;								      						      
							    default: ?>
							      <div class="alert alert-danger">
							        Unknown error occurred. Please try again.
							      </div>
							      <?php break;
							  endswitch; ?>
							<?php endif; ?>

	              <h3 class="login-heading mb-4">Welcome back!</h3>

	              <!-- Login -->
	              <form action="auth.php" method="post" novalidate>
	                <div class="form-floating mb-3">

	                  <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php echo isset($_GET['email']) ? htmlentities($_GET['email']) : ''; ?>" required>
	                  <label for="floatingInput">Email address</label>
	                </div>
	                <div class="form-floating mb-3">
	                  <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
	                  <label for="floatingPassword">Password</label>
	                </div>

	                <div class="d-grid">
	                  <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2" type="submit">Login</button>
	                </div>

	              </form>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

 
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/jquery-3.6.0.min.js"></script>
	<script>
	  document.querySelector('form').addEventListener('submit', function (event) {
	    if (!event.target.checkValidity()) {
	      event.preventDefault();
	      event.stopPropagation();
	    }
	    event.target.classList.add('was-validated');
	  });
	</script>  
  </body>
</html>