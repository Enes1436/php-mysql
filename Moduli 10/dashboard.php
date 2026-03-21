<!DOCTYPE html>
<html lang="en">
<head>
  
    <title>Document</title>
</head>
   <style>
   table, th, td {
  border: 1px solid black;
  border-collapse:collapse;
}
td,th{
    padding: 10px;
}

   </style> 
<body>



<?php
include_once('config.php');
$sql = "SELECT * FROM users";
$getusers = $connect->prepare($sql);
$getusers->execute();
$users=$getusers->fetchAll();

?>



<table>
<thead>
    <th>ID</th>
    <th>Username</th>
    <th>Name</th>
    <th>Password</th>
    <th>Email</th>

</thead>

<tbody>

<?php foreach($users as $user){?>
    <tr>
        <td><?php  $user['id']; ?></td>
        <td><?php  $user['username']; ?></td>
        <td><?php  $user['name']; ?></td>
        <td><?php  $user['password']; ?></td>
        <td><?php  $user['email']; ?></td>
    </tr>
    <?php
    }
  ?>
</tbody>
<a href="add.php">Add User</a>
</table>
</body>
</html>