<?php

class App
{
	
	public $connect;
	public $message;
	
	public function __construct($username = '', $password = '', $host = 'localhost', $database = '')
	{
		$this->connect = new mysqli($host, $username, $password, $database);

		if ($this->connect->connect_error)
		{
			die('Connection failed: ' . $this->connect->connect_error);
		}
	}
	
	public function prepareRequests()
	{
		if(!$this->connect) die('Error: Connect is wrong!');
		
		if(isset($_POST['action']) && $_POST['action'] == 'addOrder')
		{
			if(!isset($_POST['user_id']) || !(int) $_POST['user_id']) $this->message = array('type' => 'error', 'content' => 'Ошибка: Пожалуйста, укажите пользователя!');
			if(!isset($_POST['total_price']) || !(int) $_POST['total_price']) $this->message = array('type' => 'error', 'content' => 'Ошибка: Пожалуйста, укажите общую сумму!');
			if(!isset($_POST['description']) || !trim($_POST['description'])) $this->message = array('type' => 'error', 'content' => 'Ошибка: Пожалуйста, укажите описание!');
			if(!isset($_POST['contacts']) || !trim($_POST['contacts'])) $this->message = array('type' => 'error', 'content' => 'Ошибка: Пожалуйста, укажите контактную информацию!');

			if(!$this->message)
			{
				if($this->addNewOrder($_POST)) $this->message = array('type' => 'success', 'content' => 'Новый заказ успешно добавлен в базу данных!');
			}
		}
	}
	
	private function addNewOrder($data = array())
	{
		if(!$this->connect) die('Error: Connect is wrong!');

		if($this->connect->query('INSERT INTO `orders` (`id`, `user_id`, `time`, `total_price`, `description`, `contacts`, `payed`) VALUES (
			NULL,
			\'' . (int) $data['user_id'] . '\',
			\'' . $_SERVER['REQUEST_TIME'] . '\',
			\'' . (int) $data['total_price'] . '\',
			\'' . $this->connect->real_escape_string($data['description']) . '\',
			\'' . $this->connect->real_escape_string($data['contacts']) . '\',
			\'' . (int) $data['payed'] . '\'
		)'))
		{
			return true;
		}
		else
		{
			$this->message = array('type' => 'error', 'content' => $this->connect->error);
		}
	}
	
	public function getAllOrders($userID = null)
	{
		if(!$this->connect) die('Error: Connect is wrong!');
			
		$output = array();
		
		$sql = 'SELECT * FROM `orders`' . ($userID ? ' WHERE `user_id` = ' . $userID : '') . ' ORDER BY `time` DESC, `id` DESC';
		
		$res = $this->connect->query($sql);

		if($res->num_rows > 0)
		{
			while($row = $res->fetch_assoc()) $output[$row['id']] = $row;
		}

		return $output;
	}
	
	public function getAllUsers()
	{
		if(!$this->connect) die('Error: Connect is wrong!');
			
		$output = array();
		
		$sql = 'SELECT * FROM `users` ORDER BY `id` DESC';
		
		$res = $this->connect->query($sql);

		if($res->num_rows > 0)
		{
			while($row = $res->fetch_assoc()) $output[$row['id']] = $row;
		}

		return $output;
	}
	
	public function close()
	{
		$this->connect->close();
	}
	
	public function getOneOrder()
	{
		if(!isset($_REQUEST['id']) || !(int) $_REQUEST['id']) die('Error: Order ID is wrong!');
		
		if(!$this->connect) die('Error: Connect is wrong!');
		
		$result = $this->connect->query('SELECT * FROM `orders` WHERE `id` = ' . (int) $_REQUEST['id']);

		while ($row = $result->fetch_assoc()) return $row;
	}
	
	public function getOneUser()
	{
		if(!isset($_REQUEST['id']) || !(int) $_REQUEST['id']) die('Error: User ID is wrong!');
		
		if(!$this->connect) die('Error: Connect is wrong!');
		
		$result = $this->connect->query('SELECT * FROM `users` WHERE `id` = ' . (int) $_REQUEST['id']);

		while ($row = $result->fetch_assoc()) return $row;
	}
}

$host = 'localhost';
$username = 'testtask';
$password = 'testtask';

$app = new App($username, $password, 'localhost', 'testtask');

?>