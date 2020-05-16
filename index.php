<?php
use App\URLHelper;
use App\TableHelper;
use App\NumberHelper;
define('PER_PAGE',20);

require 'vendor/autoload.php';
$pdo= new PDO("sqlite:./products.db", null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$query = "SELECT * FROM products";
$queryCount ="SELECT COUNT(id) as count FROM products";
$params = [];
$sortable = ["id", "name", "city", "price", "address"];

//search by city
if(!empty($_GET['q'])){
    $query .= " WHERE city LIKE :city";
    $queryCount .= " WHERE city LIKE :city";
    $params['city'] = '%' . $_GET['q'] . '%';
}

//organization
if(!empty($_GET['sort'] && in_array($_GET['sort'], $sortable))){
    //list's direction
    $direction= $_GET['dir'] ?? 'asc';
    //avoid url injection
    if(!in_array($direction, ['asc','desc'])) {
        $direction = 'asc';
    }
    $query .= " ORDER BY " . $_GET['sort'] . " $direction";
}

//paging
//default page is first page
$page = (int)($_GET['p'] ?? 1);
//current page -1 * x
$offset = ($page - 1) * PER_PAGE;


$query .= " LIMIT " . PER_PAGE . " OFFSET $offset";

$statement = $pdo->prepare($query);
$statement->execute($params);
$products = $statement->fetchAll();
//dd($products);

$statement = $pdo->prepare($queryCount);
$statement->execute($params);
$count = $statement->fetch()['count'];
//know how many pages the list includes
$pages = ceil($count / PER_PAGE);
//dd($count);


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
            <input type="text" class="form-control" name="q" placeholder="search by city" value="<?= htmlentities($_GET['q'] ?? null) ?>">
        </div>
        <button class="btn btn-primary">Search</button>

    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= TableHelper::sort('id', 'ID', $_GET) ?></th>
                <th><?= TableHelper::sort('name', 'Name', $_GET) ?></th>
                <th><?= TableHelper::sort('price', 'Price', $_GET) ?></th>
                <th><?= TableHelper::sort('city', 'City', $_GET) ?></th>
                <th><?= TableHelper::sort('address', 'Address', $_GET) ?></th>
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
    <!--Previous page-->
    <?php if($pages > 1 && $page > 1): ?>
        <a href="?<?= URLHelper::withParam($_GET, "p", $page - 1) ?>" class="btn btn-primary">Previous page</a>
    <?php endif ?>
    <!--Next page-->
    <?php if($pages > 1 && $page < $pages): ?>
        <a href="?<?= URLHelper::withParam($_GET,"p", $page + 1) ?>" class="btn btn-primary">Next page</a>
    <?php endif ?>
</body>