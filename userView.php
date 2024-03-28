<?php

error_reporting(E_ALL);

require_once('app.php');

global $app;

$app->prepareRequests();

$user = $app->getOneUser();
$orders = $app->getAllOrders($user['id']);

$payments = 0;

if($orders)
{
	foreach($orders as $order)
	{
		if((int) $order['payed'] == 1) $payments = $payments + $order['total_price'];
	}
}

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
			<h2>Карточка пользователя № <?php echo $user['id']; ?></h2>
			<?php if($app->message) { ?>
				<p style="<?php echo ($app->message['type'] == 'error') ? 'color:red' : 'color:green'; ?>"><b><?php echo $app->message['content']; ?></b></p>
			<?php } ?>
			<table width="600" cellspacing="0" class="no-border">
				<tr>
					<td width="200">
						<u>Имя</u>
					</td>
					<td>
						<?php echo $user['name']; ?>
					</td>
				</tr>
				<tr>
					<td width="200">
						<u>Фамилия</u>
					</td>
					<td>
						<?php echo $user['surname']; ?>
					</td>
				</tr>
				<tr>
					<td width="200">
						<u>Возраст</u>
					</td>
					<td>
						<?php echo $user['age']; ?> лет.
					</td>
				</tr>
				<tr>
					<td width="200">
						<u>Всего заказов</u>
					</td>
					<td>
						<?php echo count($orders); ?>
					</td>
				</tr>
				<tr>
					<td width="200">
						<u>Всего оплат</u>
					</td>
					<td>
						<?php echo number_format($payments, 0, ',', ' '); ?> руб.
					</td>
				</tr>
			</table>
			<h2>Заказы</h2>
			<?php if(count($orders)) { ?>
				<table width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Дата / Время</th>
							<th>Общая сумма</th>
							<th>Оплачен?</th>
							<th>Описание</th>
							<th>Контактная информация</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($orders as $order) { ?>
							<tr>
								<td><?php echo $order['id']; ?></td>
								<td><?php echo date('d.m.Y / H:i:s', $order['time']); ?></td>
								<td><?php echo number_format($order['total_price'], 0, ',', ' '); ?> руб.</td>
								<td><?php echo ((int) $order['payed'] == 1) ? 'Да' : 'Нет'; ?></td>
								<td><?php echo $order['description']; ?></td>
								<td><?php echo $order['contacts']; ?></td>
								<td><a href="/orderView.php?id=<?php echo $order['id']; ?>">Перейти</a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
		</div>
	</body>
</html>