<?php
class Controller
{
private $pages;
public function __construct()
{
	$this->pages = array(
		'login' => 'login.php',
		'supply_manager' => 'contracts.php',
		'market_manager'=>'suppliers.php'); 
}
public function redirect($path)
{
	header("location: {$this->pages[$path]}");
}
}
$controller = new Controller();
?>