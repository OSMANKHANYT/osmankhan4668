<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $rank = $unit = $address = $room_no = $ip_address = $issue_date = "";
$name_err = $rank_err = $unit_err = $address_err = $room_no_err = $ip_address_err = $issue_date_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";
    } else{
        $name = trim($_POST["name"]);
    }
    
    // Validate rank
    if(empty(trim($_POST["rank"]))){
        $rank_err = "Please enter rank.";
    } else{
        $rank = trim($_POST["rank"]);
    }
    
    // Validate unit
    if(empty(trim($_POST["unit"]))){
        $unit_err = "Please enter unit.";
    } else{
        $unit = trim($_POST["unit"]);
    }
    
    // Validate address
    if(empty(trim($_POST["address"]))){
        $address_err = "Please enter address.";
    } else{
        $address = trim($_POST["address"]);
    }
    
    // Validate room number
    if(empty(trim($_POST["room_no"]))){
        $room_no_err = "Please enter room number.";
    } else{
        $room_no = trim($_POST["room_no"]);
    }
    
    // Validate IP address
    if(empty(trim($_POST["ip_address"]))){
        $ip_address_err = "Please enter IP address.";
    } elseif(!filter_var(trim($_POST["ip_address"]), FILTER_VALIDATE_IP)){
        $ip_address_err = "Please enter a valid IP address.";
    } else{
        $ip_address = trim($_POST["ip_address"]);
    }
    
    // Validate issue date
    if(empty(trim($_POST["issue_date"]))){
        $issue_date_err = "Please enter issue date.";
    } else{
        $issue_date = trim($_POST["issue_date"]);
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($rank_err) && empty($unit_err) && empty($address_err) && 
       empty($room_no_err) && empty($ip_address_err) && empty($issue_date_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO personnel_data (name, rank, unit, address, room_no, ip_address, issue_date) 
                VALUES (:name, :rank, :unit, :address, :room_no, :ip_address, :issue_date)";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":rank", $param_rank, PDO::PARAM_STR);
            $stmt->bindParam(":unit", $param_unit, PDO::PARAM_STR);
            $stmt->bindParam(":address", $param_address, PDO::PARAM_STR);
            $stmt->bindParam(":room_no", $param_room_no, PDO::PARAM_STR);
            $stmt->bindParam(":ip_address", $param_ip_address, PDO::PARAM_STR);
            $stmt->bindParam(":issue_date", $param_issue_date, PDO::PARAM_STR);
            
            // Set parameters
            $param_name = $name;
            $param_rank = $rank;
            $param_unit = $unit;
            $param_address = $address;
            $param_room_no = $room_no;
            $param_ip_address = $ip_address;
            $param_issue_date = $issue_date;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: welcome.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Personnel Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{ width: 600px; padding: 20px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Add Personnel Data</h2>
        <p>Please fill this form to add personnel data.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Rank</label>
                <input type="text" name="rank" class="form-control <?php echo (!empty($rank_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $rank; ?>">
                <span class="invalid-feedback"><?php echo $rank_err; ?></span>
            </div>
            <div class="form-group">
                <label>Unit</label>
                <input type="text" name="unit" class="form-control <?php echo (!empty($unit_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $unit; ?>">
                <span class="invalid-feedback"><?php echo $unit_err; ?></span>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                <span class="invalid-feedback"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group">
                <label>Room Number</label>
                <input type="text" name="room_no" class="form-control <?php echo (!empty($room_no_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $room_no; ?>">
                <span class="invalid-feedback"><?php echo $room_no_err; ?></span>
            </div>
            <div class="form-group">
                <label>IP Address</label>
                <input type="text" name="ip_address" class="form-control <?php echo (!empty($ip_address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ip_address; ?>">
                <span class="invalid-feedback"><?php echo $ip_address_err; ?></span>
            </div>
            <div class="form-group">
                <label>Issue Date</label>
                <input type="date" name="issue_date" class="form-control <?php echo (!empty($issue_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $issue_date; ?>">
                <span class="invalid-feedback"><?php echo $issue_date_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>