<?php
    require_once 'config.php';
    $name = $address = $salary ="";
    $name_err = $address_err = $salary_err = ""; 
    //check
    if(isset($_POST["id"]) && !empty($_POST["id"])) {

        //get hidden input value
        $id = $_POST["id"];

        //validate name
        $input_name = trim($_POST["name"]);
        if(empty($input_name)) {
            $name_err = "Please enter a name.";
        } elseif (!filter_var(trim($_POST["name"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s]+$/")))) {
            $name_err = "Please enter a valid name.";
        } else {
            $name = $input_name;
        }

         //Validate address
         $input_address = trim($_POST["address"]);
         if(empty($input_address)) {
             $address_err = "Please enter an address";
         } else {
             $address = $input_address;
         }

         //Validate salary
         $input_salary = trim($_POST["salary"]);
         if(empty($input_salary)) {
             $salary_err = "please enter the salary amount";
         } else {
             $salary = $input_salary;
         }

         //check input error before insert into database
         if(empty($name_err) && empty($address_err) && empty($salary_err)) {
            //prepare an insert statement
            $sql = "UPDATE employees SET name=?, address=?, salary=? WHERE id=?";

            if($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_address, $param_salary, $param_id);

                $param_name = $name;
                $param_address = $address;
                $param_salary = $salary;
                $param_id = $id;

                if(mysqli_stmt_execute($stmt)) {
                    header("location: index.php");
                    exit();
                } else {
                    echo "Something went wrong";
                }
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
        }  else {

        //check existing id
        if(isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
            //get url parameter
            $id = trim($_GET["id"]);

            //prepare a select statement
            $sql = "SELECT * FROM employees WHERE id = ?";
            if($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $param_id);

                //set parametres
                $param_id = $id;

                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if(mysqli_num_rows($result) == 1) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                        $name = $row["name"];
                        $address = $row["address"];
                        $salary = $row{"salary"};
                    } else {
                        header("location: error.php");
                        exit();
                    }
                } else {
                    echo "oops! soething went wrong";
                }
            
            }
            //Close statement
            mysqli_stmt_close($stmt);

            //close connection
            mysqli_close($link); 
        } else {
            //URL doesn't contain id parameter redirect to error.php
            header("location:error.php");
            exit();
        }

    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
        <div class="wrapper">
            <div class="container-field">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h2>Update Record</h2>
                        </div>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                                <span class="help-block"><?php echo $name_err; ?></span>
                            </div>
                            <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>" >
                                <label>Address</label>
                                <textarea type="text" name="address" class="form-control"><?php echo $address; ?></textarea>
                                <span class="help-block"><?php echo $address_err; ?></span>
                            </div>
                            <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                                <label>Salary</label>
                                <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
                                <span class="help-block"><?php echo $salary_err; ?></span>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a href="index.php" class="by=tn btn-default">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
</body>
</html>