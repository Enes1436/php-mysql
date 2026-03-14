<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display profucts</title>
    <style>
        table, th, td {
            border: 1px solid black;
        
        }
        td,th{
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php
    include_once('config.php');
    $sql = 'SELECT * FROM products';
    $getproducts = $connect->prepare($sql);
    $getproducts->execute();
    $products = $getproducts->fetchAll() ;
    ?>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th></th>
    </tr>

    <tr>
        <?php foreach($products as $product){ ?>
            <td><?php echo $product['id'] ?></td>
            <td><?php echo $product['name'] ?></td>
            <td><?php echo $product['price'] ?></td>
            <td><?php echo $product['category_id'] ?></td>
    </tr>
        <?php } ?>
 </table>



</body>
</html>