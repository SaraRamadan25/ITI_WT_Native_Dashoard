<?php
ob_start();
require_once '../connection.php';
include_once '../aside.php';
if(isset($_GET['code'])){
    $code=$_GET['code'];
}else{
    echo "<h2> align='center'> you aren't allowed to make/show this </h2>";
    exit();
}

$std_result=$connection->query("select * from students where code=$code");
$std_data=$std_result->fetch(PDO::FETCH_ASSOC);
$dept_result=$connection->query("select * from departments");
$dept_data=$dept_result->fetchAll(PDO::FETCH_ASSOC);

function validate($input){
    $input=trim($input); 
    $input=htmlspecialchars($input);
    $input=stripslashes($input);
    return $input;
}

function check_int($input,$key,$error){
    global $errors;
    if(! filter_var($input,FILTER_VALIDATE_INT)){
        $errors[$key]=$error;
    }
}

function check_unique($table,$column,$input,$key,$error){
    global $errors;
    global $connection;
    $input_result=$connection->query("select $column from $table where $column='$input'");
    $input_count=$input_result->rowCount();
    if($input_count > 0){
        $errors[$key]=$error;
    }
}

if (isset($_POST['edit'])){
    $fname=validate($_POST['fname']);
    $lname=validate($_POST['lname']);
    $address=validate($_POST['address']);
    $code=validate($_POST['code']);
    $password=$_POST['password'];
    $email=validate($_POST['email']);

    check_int($code,'code_int',"the code must be only numbers");
    check_unique('students','email',$email,'email_unique','this email already exists.');
    check_unique('students','code',$code,'code_unique','this code already exists.');

    if ($password<3)
    {
        $errors['password_length']="please enter a valid password";
    }
    if(empty($fname)){
        $errors['fname_required']="please enter your fname.";
    }
    if(empty($lname)){
        $errors['lname_required']="please enter your lname.";
    }

    if(empty($address)){
        $errors['address_required']="please enter your phone..";
    }
    if(empty($code)){
        $errors['code_required']="please enter your code..";
    }
    if(empty($password)){
        $errors['password_required']="please enter your password..";
    }
    if(empty($department)){
        $errors['department_required']="please enter your department..";
    }
    if(empty($email)){
        $errors['email_required']="please enter your email..";
    }

    $fname=strtolower($fname);
    $fname=ucwords($fname);
    $lname=strtolower($lname);
    $lname=ucwords($lname);

    $password=sha1($password);

    if(empty($errors)){
        $result=$connection->query("UPDATE `students` SET `fname`='$fname',`lname`='$lname',`address`='$address','password'=$password,`code`=$code,`email`='$email' WHERE code=$code");
        if($result){
            header("location: index.php");
        }
    }
}

?>
    <div class="content">
    <div class="animated fadeIn">


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Edit Student</strong>
                    </div>
                    <div class="card-body card-block">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Code</label></div>
                                <div class="col-12 col-md-9">
                                    <input type="text" id="text-input" name="code" placeholder="Text" class="form-control" value="<?php echo $std_data['code'] ?>">
                                    <?php if(isset($errors['code_required'])){ ?>
                                        <small class="form-text text-muted" style="color:red !important"><?php echo $errors['code_required'] ?></small>
                                    <?php } ?>
                                    <?php if(isset($errors['code_unique'])){ ?>
                                        <small class="form-text text-muted" style="color:red !important"><?php echo $errors['code_unique'] ?></small>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">First Name</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="fname" placeholder="Text" class="form-control" value="<?php echo $std_data['fname'] ?>"><small class="form-text text-muted">This is a help text</small></div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Last Name</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="lname" placeholder="Text" class="form-control" value="<?php echo $std_data['lname'] ?>"><small class="form-text text-muted">This is a help text</small></div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Address</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="address" placeholder="Text" class="form-control" value="<?php echo $std_data['address'] ?>"><small class="form-text text-muted">This is a help text</small></div>
                            </div>


                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">email</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="email" placeholder="Email" class="form-control" value="<?php echo $std_data['email'] ?>"><small class="form-text text-muted">This is a help text</small></div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Password</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="text-input" name="password" placeholder="Text" class="form-control" value="<?php echo $std_data['password'] ?>"><small class="form-text text-muted">This is a help text</small></div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3"><label for="select" class=" form-control-label">Select</label></div>
                                <div class="col-12 col-md-9">
                                    <select name="department" id="select" class="form-control">
                                        <?php foreach($dept_data as $dept){ ?>
                                            <option value="<?php echo $dept['number'] ?>" <?php if($dept['number'] == $std_data['dept_num']){ ?> selected <?php } ?>> <?php echo $dept['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm" name="edit">
                                    <i class="fa fa-dot-circle-o"></i> Edit
                                </button>
                                <button type="reset" class="btn btn-danger btn-sm">
                                    <i class="fa fa-ban"></i> Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
include_once '../footer.php';
ob_end_flush();
?>