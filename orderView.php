<?php

error_reporting(E_ALL);

require_once('app.php');

global $app;

$app->prepareRequests();

$order = $app->getOneOrder();
$users = $app->getAllUsers();

?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="assets/style.css"/>
		<script src="assets/jquery.min.js"></script>
		<script src="assets/scripts.js"></script>
	</head>
	<body>
		<div class="wrap">
			<p><a href="/">Все заказы</a></p>
			<h2>Карточка заказа № <?php echo $order['id']; ?></h2>
			<?php if($app->message) { ?>
				<p style="<?php echo ($app->message['type'] == 'error') ? 'color:red' : 'color:green'; ?>"><b><?php echo $app->message['content']; ?></b></p>
			<?php } ?>
			<table width="600" cellspacing="0" class="no-border">
				<tr>
					<td width="200">
						<u>Пользователь</u>
					</td>
					<td>
						<?php echo isset($users[$order['user_id']]) ? '<a href="/userView.php?id=' . $users[$order['user_id']]['id'] . '">' . $users[$order['user_id']]['name'] . ' ' . $users[$order['user_id']]['surname'] . '</a>' : ''; ?>
					</td>
				</tr>
				<tr>
					<td width="200">
						<u>Дата / Время</u>
					</td>
					<td>
						<?php echo date('d.m.Y / H:i:s', $order['time']); ?>
					</td>
				</tr>
				<tr>
					<td width="200">
						<u>Общая сумма</u>
					</td>
					<td>
						<?php echo number_format($order['total_price'], 0, ',', ' '); ?> руб.
					</td>
				</tr>
				<tr>
					<td width="200">
						<u>Описание</u>
					</td>
					<td>
						<?php echo $order['description']; ?>
					</td>
				</tr>
				<tr>
					<td width="200">
						<u>Контактная информация</u>
					</td>
					<td>
						<?php echo $order['contacts']; ?>
					</td>
				</tr>
				<tr>
					<td width="200">
						<u>Статус оплаты</u>
					</td>
					<td>
						<?php echo ((int) $order['payed'] == 1) ? 'Оплачен' : 'Не оплачен'; ?>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>