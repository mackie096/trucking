<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>  .lgn-btn, .sgn-btn, .track-btn{opacity: .8} .lgn-btn:hover, .sgn-btn:hover, .track-btn:hover{opacity:1} .forgot-text{ color: #0000cd; } .forgot-text:hover{color: #ff0000;} </style>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header class="header-size"><nav>
        <li><a class="navbar" href="index.php">Home</a></li>
        <li><a class="navbar" href="service.html">Service</a></li>
        <li><a class="navbar" href="contact.html">Contact</a></li>
    </nav></header>
    <div class="grid-layout">
        <!--Login box-->
        <div class="login">    
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <img class="login-logo" src="images/login-logo.png"><br>
                <i class="fa fa-user icon"></i>
                <input class="login-textbox <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>" type="text" placeholder="Username..." name="username"><br>
                 <span class="help-block"><?php echo $username_err; ?></span>
                <i class="fa fa-key icon"></i>
                <input class="login-textbox <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>" type="password" placeholder="Password..." name="password"><br>
                <span class="help-block"><?php echo $password_err; ?></span>
                <label>
                    <input type="checkbox" unchecked="checked" name="remember"> Remember me
                </label><br>
                <input type="submit" value="Login" class="lgn-btn lgn-btn-position">
                </form>
                <a href="register.php"><input type="submit" value="Signup" class="lgn-btn sgn-btn-position"></a>
           <br>
                <a href="forgot.php" class="forgot-text"><p>Forgot password?</p></a>
        </div>
        <!--slide box-->
        <div class="slides">          
                <img src="images/slide1.jpg" class="slide fading slide-picture">
                <img src="images/slide2.jpg" class="slide left slide-picture">          
                <img src="images/slide3.jpg" class="slide right slide-picture">
        </div>
        <!--track box-->
        <div class="track position">
            <h4>Track shipment here: </h4>
                <input class="track-textbox" type="text" placeholder=" Tracking number...">
                 <button onclick="document.getElementById('id01').style.display='block'" class="track-btn" style="margin-left: -5px;">Search</button>
        </div>

    </div>
    <footer>
    
        <div class="footer-boxes">
            <div class="about">
                <img src="images/faster.jpg" class="aboutimg">
                <div class="overlay">
                <div class="text"> <h1>Faster</h1>Hello
                <p>"Part of our goal is to provide a faster service than other competitor."</p></div>
                </div>
            </div>
            <div class="about">
                <img src="images/cheaper.jpg" class="aboutimg">
                <div class="overlay">
                <div class="text"> 
                <h1>Cheaper</h1>
                <p>"To please our clients is to provide a cheaper price but with the same service as epxress."</p></div></div>
            </div>
            <div class="about">
                
                <img src="images/track.jpg" class="aboutimg">
                <div class="overlay">
                <div class="text">
                <h1>Trackable</h1>
                <p>"It is important to trace your products by phone or by your personal computer. We provide a track-and-trace service to better assist our clients.s"</p>
            </div>
                </div>
            </div>
        </div>
    </footer>
    <div id="id01" class="modal modal-content animate">
    <div class="container">
        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
  
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cnl">Close</button>
     </div>
</div>
<script src="js/slidepicture.js"></script>
</body>
</html>