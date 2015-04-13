<?php
/*
	Author - Anjana Wijesundara
	Contact - wsanjana951@gmail.com
*/
require 'Slim/Slim.php';

$app = new Slim();

$app->get('/Customers', 'getCustomers');
$app->get('/Customers/:id', 'getCustomer');
$app->post('/New_Customer', 'addCustomer');
$app->put('/Customers/:id', 'updateCustomer');
$app->delete('/Customers/:id', 'deleteCustomer');

$app->run();

// Get Database Connection
function DB_Connection() {	
	$dbhost = "127.0.0.1";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "angular_crud";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
//Get Customer Details
function getCustomers() {
	$sql = "select id,Username,First_Name,Last_Name,Email,Status FROM customers";
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$list = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

// Add new Customer to the Database
function addCustomer() {
	$request = Slim::getInstance()->request();
	$cus = json_decode($request->getBody());

	$sql = "INSERT INTO customers (Username, First_Name, Last_Name, Email, Status) VALUES (:username, :firstname, :lastname, :email, :status)";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("username", $cus->Username);
		$stmt->bindParam("firstname", $cus->First_Name);
		$stmt->bindParam("lastname", $cus->Last_Name);
		$stmt->bindParam("email", $cus->Email);
		$stmt->bindParam("status", $cus->Status);
		$stmt->execute();
		$cus->id = $db->lastInsertId();
		$db = null;
		echo json_encode($cus); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
// GET One Customer Details
function getCustomer($id) {
	$sql = "select id,Username,First_Name,Last_Name,Email,Status FROM customers WHERE id=".$id." ORDER BY id";
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$list = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
//Update Cutomer Details
function updateCustomer($id) {
	$request = Slim::getInstance()->request();
	$cus = json_decode($request->getBody());

	$sql = "UPDATE customers SET Username=:username,First_Name=:firstname,Last_Name=:lastname, Email=:email, Status=:status WHERE id=:id";
	try {
		$db = DB_Connection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("username", $cus->Username);
		$stmt->bindParam("firstname", $cus->First_Name);
		$stmt->bindParam("lastname", $cus->Last_Name);
		$stmt->bindParam("email", $cus->Email);
		$stmt->bindParam("status", $cus->Status);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($cus); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
//DELETE Customer From the Database
function deleteCustomer($id) {
	$sql = "DELETE FROM customers WHERE id=".$id;
	try {
		$db = DB_Connection();
		$stmt = $db->query($sql);  
		$list = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($list);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

?>