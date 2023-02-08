<?php
if(isset($_POST['submit'])){
    $code = $_POST['code'];
    $imageName=$_FILES['image']['name'];
    $tmpName=$_FILES['image']['tmp_name'];
    $path = "upload/images/$code.$imageName";

    $allowedExtenstions=['png','jpg','jpeg','web'];
    $strToArray=explode(".",$imageName);
    $extesntion = end($strToArray);
    if (in_array($extesntion,$allowedExtenstions)){
        move_uploaded_file($tmpName,$path);

    }
}
?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">
    <input type="text" name="code">
    <input type="file" name="image">
    <input type="submit"  value="submit" name="submit">


</form>
