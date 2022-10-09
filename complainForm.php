<?php 
    require_once "config.php"; 
    // PHP 
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $FormUsername = trim($_POST["FormUsername"]);
        $text = trim($_POST["text"]);
        // after post 
        if(empty(trim($_POST["FormUsername"]))){  
           echo "<p class='error errorMsg3'>Please enter a username.</p>";
        } elseif(!preg_match('/^[a-zA-Z0-9_\s]+$/', trim($_POST["FormUsername"]))){
            echo "<p class='error errorMsg3'>Username can only contain letters, numbers, and underscores.</p>";
        } else{
           // Prepare an insert statement
        $sql = "INSERT INTO feedback (username_fb, fb_text) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username_fb, $text);
            
            // Set parameters
            $param_username_fb = $FormUsername;
            $text = $text;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                echo "<p class='error errorMsg4'>Thank you.</p>";
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
        }
    } 

    ?>

<?php include 'header.php'; ?>


<div class="wrapper">
        <h2>Complain form</h2>
        <!-- Form  -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div >
                <input autocomplete="off" class="btn form-group" placeholder='NAME' type="text" name="FormUsername" class="form-control" value="" pattern="([A-z0-9À-ž\s]){2,}">
            </div>    
            <div >
                <textarea autocomplete="off" class="textarea-group" placeholder='WRITE YOUR TEXT.' rows="5" cols="40" name="text" value=""></textarea> 
            </div>    
           
            <div class="form-group">
                <input type="submit" class="btn btn-primary goToSubmit" value="Submit">
            </div>
        </form>
        <!-- Log in button -->
        <div class="btn goToComplainForm goToLogin">
            <a href="login.php">Log in</a>
        </div>
    </div>    
    
</body>
</html>