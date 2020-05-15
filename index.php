<?php
use App\NumberHelper;

require 'vendor/autoload.php';
$pdo= new PDO("sqlite:./products.db", null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$query = "SELECT * FROM products";
$params = [];

//search by city
if(!empty($_GET['q'])){
    $query .= " WHERE city LIKE :city";
    $params['city'] = '%' . $_GET['q'] . '%';
}

$query .= " LIMIT 20";

$statement = $pdo->prepare($query);
$statement->execute($params);
$products = $statement->fetchAll();
//dd($products);

?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Real Estate</title>
</html>

<body>
    <h1>Real Estates</h1>
    <form action="" class="mb-4">
        <div class="form-group">
            <!-- name="q" give a search information for seo-->
            <input type="text" class="form-control" name="q" placeholder="search by city">

        </div>
        <button class="btn btn-primary">Search</button>

    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>City</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            <tr>
                <td>#<?= $product['id'] ?></td>
                <td><?= $product['name'] ?></td>
                <td><?= NumberHelper::price($product['price']); ?></td>
                <td><?= $product['city'] ?></td>
                <td><?= $product['address'] ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>