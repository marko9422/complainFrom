<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?>
<?php include 'header.php'; ?>
<body>
    <h1 class="welcomeSign">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome.</h1>
    <?php 
    require_once "config.php"; 
    // PHP 
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $FormUsername = trim($_POST["FormUsername"]);
        $text = trim($_POST["text"]);
        // after post 
        if(empty(trim($_POST["FormUsername"]))){
           echo "Please enter a username.";
        } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["FormUsername"]))){
            echo "Username can only contain letters, numbers, and underscores.";
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
    <!-- Complain form for login user -->
    <div class="wrapper">
        <h2>Complain form</h2>
        <!-- Form  -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div >
                <input autocomplete="off" type="text" name="FormUsername" class="btn form-group hide" value="<?php echo htmlspecialchars($_SESSION["username"]); ?>">
            </div>    
            <div >
                <textarea autocomplete="off" class="textarea-group" placeholder='WRITE YOUR TEXT.' rows="5" cols="40" name="text" value=""></textarea> 
            </div>    
           
            <div class="form-group">
                <input type="submit" class="btn btn-primary goToSubmit" value="SUBMIT">
            </div>
             <!-- Log out btn -->
            <div class="btn goToComplainForm goToLogin">
                <a href="logout.php">SIGN OUT</a>
            </div>
        </form>


        <!-- // Write comments from database.  -->
    <?php
        $sql = "SELECT id, username_fb, fb_text FROM feedback";
        $result = mysqli_query($link, $sql);
        if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "<p class='commentName'>" . $row["username_fb"]."</p>";
            echo "<p class='commentText'>" . $row["fb_text"]."</p>";
        }
        } else {
        echo "0 results";
        }
        mysqli_close($link);
    ?>
</body>
</html>