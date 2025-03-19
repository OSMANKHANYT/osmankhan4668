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

// Define variables for messages
$success_msg = $error_msg = "";

// Process delete operation
if(isset($_POST["delete"]) && !empty($_POST["id"])){
    $sql = "DELETE FROM data_items WHERE id = :id";
    if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(":id", $_POST["id"], PDO::PARAM_INT);
        if($stmt->execute()){
            $success_msg = "Record deleted successfully.";
        } else {
            $error_msg = "Error deleting record.";
        }
        unset($stmt);
    }
}

// Process add operation
if(isset($_POST["add"])){
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    
    if(!empty($title)){
        $sql = "INSERT INTO data_items (title, description) VALUES (:title, :description)";
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->bindParam(":description", $description, PDO::PARAM_STR);
            if($stmt->execute()){
                $success_msg = "Record added successfully.";
            } else {
                $error_msg = "Error adding record.";
            }
            unset($stmt);
        }
    } else {
        $error_msg = "Title cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .wrapper{ width: 900px; padding: 20px; margin: 0 auto; }
        .table td, .table th { vertical-align: middle; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Manage Data</h2>
                    <div class="mb-3">
                        <a href="welcome.php" class="btn btn-secondary">Back to Welcome</a>
                    </div>
                    
                    <?php 
                    if(!empty($success_msg)){
                        echo '<div class="alert alert-success">' . $success_msg . '</div>';
                    }
                    if(!empty($error_msg)){
                        echo '<div class="alert alert-danger">' . $error_msg . '</div>';
                    }
                    ?>

                    <!-- Add Data Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Add New Record</h4>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <button type="submit" name="add" class="btn btn-primary">Add Record</button>
                            </form>
                        </div>
                    </div>

                    <!-- View Data Table -->
                    <div class="card">
                        <div class="card-header">
                            <h4>View Records</h4>
                        </div>
                        <div class="card-body">
                            <?php
                            // Fetch all records
                            $sql = "SELECT * FROM data_items ORDER BY id DESC";
                            if($result = $pdo->query($sql)){
                                if($result->rowCount() > 0){
                                    echo '<table class="table table-bordered table-striped">';
                                        echo '<thead>';
                                            echo '<tr>';
                                                echo '<th>ID</th>';
                                                echo '<th>Title</th>';
                                                echo '<th>Description</th>';
                                                echo '<th>Actions</th>';
                                            echo '</tr>';
                                        echo '</thead>';
                                        echo '<tbody>';
                                        while($row = $result->fetch()){
                                            echo '<tr>';
                                                echo '<td>' . $row['id'] . '</td>';
                                                echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                                                echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                                                echo '<td>';
                                                    echo '<a href="edit-data.php?id='. $row['id'] .'" class="btn btn-primary btn-sm mr-2"><i class="fas fa-edit"></i></a>';
                                                    echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" style="display: inline;">';
                                                        echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                                                        echo '<button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this record?\')"><i class="fas fa-trash"></i></button>';
                                                    echo '</form>';
                                                echo '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</tbody>';
                                    echo '</table>';
                                } else {
                                    echo '<div class="alert alert-info">No records found.</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger">Error: Could not fetch records.</div>';
                            }
                            unset($result);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>