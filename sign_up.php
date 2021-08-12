<?php
    $failFlag = false;
    $errorMessage = "Testing Error";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        require_once('config.php');
        //Make sure username not blank
        if (!isset($_POST['floatingUname']) || empty($_POST['floatingUname']))
        {
            //error
            $errorMessage = 'Username cannot be blank!';
            $failFlag = true;
        }else
        if (!isset($_POST['floatingEmail']) || empty($_POST['floatingEmail']))
        {
            //error
            $errorMessage = 'Email cannot be blank!';
            $failFlag = true;
        }else
    
        if (!isset($_POST['floatingPassword']) || empty($_POST['floatingPassword']))
        {
            //error
            $errorMessage = 'Password cannot be blank!';
            $failFlag = true;
        }else
        if (!isset($_POST['floatingPasswordConfirm']) || empty($_POST['floatingPasswordConfirm']))
        {
            //error
            $errorMessage = 'Passwords must match!';
            $failFlag = true;
        }else
        if ($_POST['floatingPasswordConfirm'] != $_POST['floatingPassword'])
        {
            //error
            $errorMessage = 'Passwords must match!';
            $failFlag = true;
        }
        if(!$failFlag){
            $password = $_POST['floatingPassword'];
            $username = trim($_POST['floatingUname']);
            $username = filter_var($username, FILTER_SANITIZE_STRING);
            $email = $_POST['floatingEmail'];
            $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
            $options = [
                'cost' => 12,
            ];
            $encryptedPass = password_hash($password, PASSWORD_BCRYPT, $options);
            $sql = "INSERT INTO users(username, password, emailAddress)
                VALUES ('$username', '$encryptedPass', '$emailSanitized');";
            if ($DBconn->query($sql) === TRUE) {
                //redirect
                $_SESSION['USERNAME'] = $row['username'];
                $_SESSION['LOGGEDIN']=TRUE;
                header('Location: https://www.cameronmorrow.com/BlogDemo/browse.php');
            }else{
                $failFlag = true;
                $errorMessage = "Something went wrong, please try again later.";
            }
        }
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Blog Demo">
        <meta name="author" content="Cameron Morrow">
        
        <title>Cameron's Blog Demo - Sign Up!</title>
        
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.css" integrity="sha256-5a0xpHkTzfwkcKzU4wSYL64rzPYgmIVf7PO4TB5/6jQ=" crossorigin="anonymous">
        
        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/js/fontawesome.min.js" integrity="sha256-xLAK3iA6CJoaC89O/DhonpICvf5QmdWhcPJyJDOywJM=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }
            
            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
            html,
            body {
                height: 100%;
            }
            
            body {
                display: flex;
                align-items: center;
                padding-top: 40px;
                padding-bottom: 40px;
                background-color: #f5f5f5;
            }
            
            .form-signin {
                width: 100%;
                max-width: 330px;
                padding: 15px;
                margin: auto;
            }
            
            .form-signin .checkbox {
                font-weight: 400;
            }
            
            .form-signin .form-floating:focus-within {
                z-index: 2;
            }
            
            .form-signin input[type="email"] {
                margin-bottom: -1px;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }
            
            .form-signin input[type="password"] {
                margin-bottom: 10px;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }
        </style>
		
        <?php if($failFlag):?>
		<script>
			$(document).ready(function(){
				$('#ErrorTitle').text("Login Error");
				$('#ErrorBody').text("<?php echo $errorMessage; ?>");
				$('#ErrorModal').modal('show');
			});
		</script>
		<?php endif;?>

		<script type="text/javascript">
			$(document).ready(function(){
				$("#floatingUname").on('input', function() {
					ValidateInput();
				});
				$("#floatingPassword").on('input', function() {
					ValidateInput();
				});
				$("#floatingEmail").on('input', function() {
					ValidateInput();
				});
				$("#floatingPasswordConfirm").on('input', function() {
					ValidateInput();
				});
			});
			function ValidateInput() {
				$.post( "check-username-exists.php", { user: $("#floatingUname").val(), email: $("#floatingEmail").val(), pword: $("#floatingPassword").val(), confirm: $("#floatingPasswordConfirm").val() }, function (data){
					var data = $.parseJSON(data);
					if(data.UsernameErrors.length == 0){
						$("#user-error").addClass("hide");
						$("#user-error").removeClass("fail");
						$("#floatingUname").removeClass("is-invalid");
						$("#floatingUname").addClass("is-valid");
						
					}else{
						$("#user-error").removeClass("hide");
						$("#user-error").removeClass("success");
						$("#user-error").addClass("fail");
						$("#floatingUname").addClass("is-invalid");
						$("#floatingUname").removeClass("is-valid");
					}

					if(data.PasswordErrors.length == 0){
						$("#pword-error").addClass("hide");
						$("#pword-error").removeClass("fail");
						$("#floatingPassword").removeClass("is-invalid");
						$("#floatingPassword").addClass("is-valid");
						
					}else{
						$("#pword-error").removeClass("hide");
						$("#pword-error").removeClass("success");
						$("#pword-error").addClass("fail");
						$("#floatingPassword").addClass("is-invalid");
						$("#floatingPassword").removeClass("is-valid");
					}

					if(data.PasswordErrors.Match){
						$("#confirm-error").removeClass("hide");
						$("#confirm-error").removeClass("success");
						$("#confirm-error").addClass("fail");
						$("#floatingPasswordConfirm").addClass("is-invalid");
						$("#floatingPasswordConfirm").removeClass("is-valid");
					}else{
						$("#confirm-error").addClass("hide");
						$("#confirm-error").removeClass("fail");
						$("#floatingPasswordConfirm").removeClass("is-invalid");
						$("#floatingPasswordConfirm").addClass("is-valid");
					}

					if(data.EmailErrors.length == 0){
						$("#email-error").addClass("hide");
						$("#email-error").removeClass("fail");
						$("#floatingEmail").removeClass("is-invalid");
						$("#floatingEmail").addClass("is-valid");
						
					}else{
						$("#email-error").removeClass("hide");
						$("#email-error").removeClass("success");
						$("#email-error").addClass("fail");
						$("#floatingEmail").addClass("is-invalid");
						$("#floatingEmail").removeClass("is-valid");
					}

					if(data.UsernameErrors.Blank){
						$("#username-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  Username cannot be blank.</span>");
					}
					if(data.UsernameErrors.SpecialChars){
						$("#username-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  Username cannot contain special characters.</i></span>");
					}
					if(data.UsernameErrors.Taken){
						$("#username-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  Username is taken.</span>");
					}

					if(data.EmailErrors.Blank){
						$("#email-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  Email cannot be blank.</span>");
					}
					if(data.EmailErrors.Invalid){
						$("#email-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  This email is invalid.</i></span>");
					}
					if(data.EmailErrors.Taken){
						$("#email-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  Email is already in use.<br><a href='login.php'>Login</a> Instead?</span>");
					}

					if(data.PasswordErrors.Blank){
						$("#pword-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  Password cannot be blank.</span>");
					}
					if(data.PasswordErrors.Short){
						$("#pword-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  Password is too weak!</span>");
					}
					if(data.PasswordErrors.Match){
						$("#confirm-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  Passwords do not match!</span>");
						$("#pword-error-message").html("<span class='error-text'><i class='fas fa-exclamation-triangle'></i>  Passwords do not match!</span>");
					}
				});
				return;
			}
        </script>
        <style>
            .hide{
                display: none;
            }
            .error-text {
                color: #D02541;
            }
            .fail{
                display: block;
                color: #D02541;
            }
            .success{
                display: block;
                color: #338450;
            }
            .success span{
                color: #F0F7F4;
            }
        </style>
    </head>
    <body class="text-center vsc-initialized">
        <main class="form-signin">
            <form method="POST" action="sign_up.php" >
                <h1 class="h2 mb-3 fw-normal">Camerons Blog Demo Sign-Up!</h1>
                
                <div class="form-floating my-2 input-group has-validation">
                    <input type="text" class="form-control" id="floatingUname" name="floatingUname" placeholder="Create a Username">
                    <label for="floatingUname" style="z-index: 10">Create a Username</label>
                </div>
                <div class="justify-content-center form-group hide" id="user-error">
				    <p id="username-error-message"><span class="error-text">User Error Message</span></p>
				</div>
				
                <div class="form-floating my-2 input-group has-validation">
                    <input type="text" class="form-control" id="floatingEmail" name="floatingEmail" placeholder="name@example.com">
                    <label for="floatingEmail" style="z-index: 10">Email address</label>
                </div>
                <div class="justify-content-center form-group hide" id="email-error">
				    <p id="email-error-message"><span class="error-text">User Error Message</span></p>
				</div>
				
				<div class="form-floating my-2 input-group has-validation">
                    <input type="password" class="form-control" id="floatingPassword" style="margin-bottom: 0px;" name="floatingPassword" placeholder="Password">
                    <label for="floatingPassword" style="z-index: 10">Password</label>
                </div>
                <div class="justify-content-center form-group hide" id="pword-error">
				    <p id="pword-error-message"><span class="error-text">User Error Message</span></p>
				</div>
				
				<div class="form-floating my-2 input-group has-validation">
                    <input type="password" class="form-control" id="floatingPasswordConfirm" style="margin-bottom: 0px;" name="floatingPasswordConfirm" placeholder="Password">
                    <label for="floatingPasswordConfirm" style="z-index: 10">Confirm Password</label>
                </div>
                <div class="justify-content-center form-group hide" id="pword-error">
				    <p id="confirm-error-message"><span class="error-text">User Error Message</span></p>
				</div>
                
                <button class="w-100 btn btn-lg btn-primary my-2" type="submit">Sign up</button>
                <a href="login.php">Return to login</a>
            </form>
        </main>
        
        <!-- Modal -->
        <div class="modal fade" id="ErrorModal" tabindex="-1" aria-labelledby="ErrorTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ErrorTitle">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="ErrorBody">
                        <p>Error</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>