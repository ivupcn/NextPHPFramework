<?php
try
{
	$conn = new PDO('sqlsrv:server=localhost;Database=vod','sa','42920617');
}
catch(Exception $e)
{
	die( print_r( $e->getMessage() ) ); 
}

try
{
	$getProducts = $conn->prepare('SELECT TOP 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY id) AS RowNumber, * FROM x_menu) A WHERE display = 0 AND RowNumber > 20 * (1)');
	$getProducts->execute();
	$products = $getProducts->fetchAll(PDO::FETCH_ASSOC);
	var_dump($products);
}
catch(Exception $e)
{
	die( print_r( $e->getMessage() ) ); 
}
?>