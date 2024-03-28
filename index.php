<?php

error_reporting(E_ALL);

require_once('app.php');

global $app;

$app->prepareRequests();

$orders = $app->getAllOrders();
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
			<?php if(count($orders)) { ?>
				<table width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>#</th>
							<th>Пользователь</th>
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
								<td><?php echo isset($users[$order['user_id']]) ? '<a href="/userView.php?id=' . $users[$order['user_id']]['id'] . '">' . $users[$order['user_id']]['name'] . ' ' . $users[$order['user_id']]['surname'] . '</a>' : ''; ?></td>
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
			<h2>Добавить заказ</h2>
			<?php if($app->message) { ?>
				<p style="<?php echo ($app->message['type'] == 'error') ? 'color:red' : 'color:green'; ?>"><b><?php echo $app->message['content']; ?></b></p>
			<?php } ?>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="pushStateForm">
				<input type="hidden" name="action" value="addOrder"/>
				<table width="500" cellspacing="0" class="no-border">
					<tr>
						<td width="150">
							<label>Пользователь</label>
						</td>
						<td>
							<select name="user_id">
								<option value="">Сделайте выбор ...</option>
								<?php if($users) { ?>
									<?php foreach($users as $user) { ?>
										<option value="<?php echo $user['id']; ?>" <?php echo (isset($_POST['user_id']) && $_POST['user_id'] && $_POST['user_id'] == $user['id']) ? 'selected="selected"' : ''; ?>><?php echo $user['name']; ?> <?php echo $user['surname']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150">
							<label>Общая сумма</label>
						</td>
						<td>
							<input type="text" name="total_price" value="<?php echo (isset($_POST['total_price']) && $_POST['total_price']) ? htmlspecialchars($_POST['total_price']) : ''; ?>"/>
						</td>
					</tr>
					<tr>
						<td width="150">
							<label>Описание</label>
						</td>
						<td>
							<textarea name="description"><?php echo (isset($_POST['description']) && $_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td width="150">
							<label>Контактная информация</label>
						</td>
						<td>
							<textarea name="contacts"><?php echo (isset($_POST['contacts']) && $_POST['contacts']) ? htmlspecialchars($_POST['contacts']) : ''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td width="150">
							<label>Статус оплаты</label>
						</td>
						<td>
							<select name="payed">
								<option value="">Сделайте выбор ...</option>
								<option value="0" <?php echo (isset($_POST['payed']) && (int) $_POST['payed'] == 0) ? 'selected="selected"' : ''; ?>>Не оплачен</option>
								<option value="1" <?php echo (isset($_POST['payed']) && (int) $_POST['payed'] == 1) ? 'selected="selected"' : ''; ?>>Оплачен</option>
							</select>
						</td>
					</tr>
				</table>
				<p>
					<input type="submit" name="submit" value="Добавить заказ"/>
				</p>
			</form>
		</div>
	</body>
</html>