<?php
    require_once('config.php');
    if(isset($_GET['return'])){
        $_SESSION['return'] = $_GET['return'];
        $_SESSION['id'] = $_GET['id'];
    }
    $failFlag = false;
    $errorMessage = "Testing Error";
    if(isset($_SESSION['LOGGEDIN']) && $_SESSION['LOGGEDIN']){
        header('Location: https://www.cameronmorrow.com/BlogDemo/browse.php');
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        //Make sure username is not blank
        if (!isset($_POST['floatingInput']) || empty($_POST['floatingInput']))
        {
            //error
            $errorMessage = "Username is blank";
            $failFlag = true;
        }
    
        if (!isset($_POST['floatingPassword']) || empty($_POST['floatingPassword']))
        {
            //error
            $errorMessage = "Password is blank";
            $failFlag = true;
        }
        if(!$failFlag){
            $password = $_POST['floatingPassword'];
            $username = trim($_POST['floatingInput']);
            $username = filter_var($username, FILTER_SANITIZE_STRING);
            $sql = "Select * from users where (username = '".$username."' or emailAddress = '".$username."');";
            $result = $DBconn->query($sql);
            if ($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                if(password_verify($password, $row['password'])){
                    $_SESSION['USERNAME'] = $row['username'];
                    $_SESSION['LOGGEDIN']=TRUE;
                    if(isset($_SESSION['return'])){
                        if($_SESSION['return'] == 'posts' || $_SESSION['return'] == 'View%20Post' || $_SESSION['return'] == 'View Post'){
                            header('Location: https://www.cameronmorrow.com/BlogDemo/view_post.php?id='.$_SESSION['id']);
                        }else{
                            header('Location: https://www.cameronmorrow.com/BlogDemo/browse.php');
                        }
                    }else{
                        header('Location: https://www.cameronmorrow.com/BlogDemo/browse.php');
                    }
                }else {
                    //login error wrong password
                    $errorMessage = "Incorrect username or password.";
                    $failFlag = true;
                }
            } else {
                //login error user doesnt exist
                $errorMessage = "Incorrect username or password.";
                $failFlag = true;
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

		<title>Login to Cameron's Blog Demo</title>

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
        
    </head>
    <body class="text-center vsc-initialized">
        <main class="form-signin">
            <form method="POST" action="login.php" >
                <h1 class="h2 mb-3 fw-normal">Camerons Blog Demo Login</h1>
                
                <div class="form-floating my-2">
                    <input type="text" class="form-control" id="floatingInput" name="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Email address or Username</label>
                </div>
                <div class="form-floating my-2">
                    <input type="password" class="form-control" id="floatingPassword" name="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                
                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>
                <button class="w-100 btn btn-lg btn-primary my-2" type="submit">Sign in</button>
                <a class="my-5 mx-3" href="sign_up.php">Sign up</a>
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
