<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "<p class='errorRegisterForm '>Please enter a username.</p>";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "<p class='errorRegisterForm '>Username can only contain letters, numbers, and underscores.</p>";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "<p class='errorRegisterForm '>This username is already taken.</p>";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "<p class='errorRegisterForm '>This username is already taken.</p>";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "<p class='errorRegisterForm '>Please enter a password.</p>";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "<p class='errorRegisterForm '>Password must have atleast 6 characters.</p>";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "<p class='errorRegisterForm '>Please confirm password.</p>";   
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "<p class='errorRegisterForm '>Password did not match.</p>";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "<p class='errorRegisterForm '>Oops! Something went wrong. Please try again later.</p>.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!-- header -->
 <?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p class='sign registerSign'>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input autocomplete="off" class="btn form-group" placeholder='USERNAME' type="text" name="username"  <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?> value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <input class="btn form-group" placeholder='PASSWORD' type="password" name="password"  <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input autocomplete="off" class="btn form-group" placeholder='CONFIRM PASSWORD' type="password" name="confirm_password"  <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input autocomplete="off" type="submit" class="btn btn-primary goToSubmit" value="Submit">
            </div>
            <p class='sign'>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>