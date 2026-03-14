<?php
include("../config/db.php");

if(isset($_POST['add_book'])){

$title = $_POST['title'];
$author = $_POST['author'];
$category = $_POST['category'];
$description = $_POST['description'];
$price = $_POST['price'];
$weight = $_POST['weight'];

$pdf = $_FILES['pdf_file']['name'];
$image = $_FILES['book_image']['name'];

move_uploaded_file($_FILES['pdf_file']['tmp_name'],
"../uploads/pdf/".$pdf);

move_uploaded_file($_FILES['book_image']['tmp_name'],
"../uploads/covers/".$image);

$sql = "INSERT INTO books
(title,author,category,description,price,pdf_file,book_image,weight,created_at)

VALUES
('$title','$author','$category','$description','$price','$pdf','$image','$weight',NOW())";

mysqli_query($conn,$sql);

echo "Book Added Successfully";

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Add Book</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
}

.container{
width:500px;
margin:40px auto;
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 0 10px #ccc;
}

input,textarea{
width:100%;
padding:10px;
margin-top:10px;
}

button{
width:100%;
padding:10px;
margin-top:15px;
background:#27ae60;
color:white;
border:none;
}

</style>

</head>

<body>

<div class="container">

<h2>Add Book</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" placeholder="Book Title" required>

<input type="text" name="author" placeholder="Author" required>

<input type="text" name="category" placeholder="Category">

<textarea name="description" placeholder="Book Description"></textarea>

<input type="number" name="price" placeholder="Price">

<input type="text" name="weight" placeholder="Weight">

<label>Upload PDF</label>
<input type="file" name="pdf_file" required>

<label>Upload Book Image</label>
<input type="file" name="book_image" required>

<button name="add_book">Add Book</button>

</form>

</div>

</body>
</html>