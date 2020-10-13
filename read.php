<?php
    if(isset($_GET["id"]) && !empty($_GET["id"])) {
        require_once 'config.php';

        $sql = "SELECT * FROM employees WHERE id = ?";

        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            $param_id = trim($_GET["id"]);

            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $name = $row["name"];
                    $address = $row["address"];
                    $salary = $row["salary"];
                } else {
                    header("location: index.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong, try again!";
            }
        }
        mysqli_stmt_close($stmt);

        mysqli_close($link);
    } else {
        header("location: error.php");
        exit();
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        .wrapper {
            width : 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="cold-md-12">
                        <div class="page-header">
                            <h1>View Record</h1>
                        </div>
                            <div class="form-group">
                                <label>Name</label>
                                <p class="form-control-static"><?php echo $row["name"]; ?></p>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <p class="form-control-static"><?php echo $row["address"]; ?></p>
                            </div>
                            <div class="form-group">
                                <label>Salary</label>
                                <p class="form-control-static"><?php echo $row["salary"]; ?></p>
                            </div>
                            <p><a href ="index.php" class="btn btn-primary">Back</a></p>
                    </div>
                </div>
            </div>
        </div>
    
</body>
</html>