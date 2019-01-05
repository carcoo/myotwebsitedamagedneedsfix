<?php require_once 'engine/init.php';
protect_page();
include 'layout/overall/header.php'; 

// Import from config:
$pagseguro = $config['pagseguro'];
$paypal = $config['paypal'];
$prices = $config['paypal_prices'];

if ($paypal['enabled']) {
?>

<h1>Buy Points</h1>
<h2>Buy points using Etisalat credit card ( contact Admin Carcoo )</h2>
<h2>Buy points using Paypal:</h2>
<table id="buypointsTable" class="table table-striped table-hover">
	<tr class="yellow">
		<th>Price:</th>
		<th>Points:</th>
		<?php if ($paypal['showBonus']) { ?>
			<th>Bonus:</th>
		<?php } ?>
		<th>Action:</th>
	</tr>
		<?php
		foreach ($prices as $price => $points) {
		echo '<tr class="special">';
		echo '<td>'. $price .'('. $paypal['currency'] .')</td>';
		echo '<td>'. $points .'</td>';
		if ($paypal['showBonus']) echo '<td>'. calculate_discount(($paypal['points_per_currency'] * $price), $points) .' bonus</td>';
		?>
		<td>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="POST">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="<?php echo $paypal['email']; ?>">
				<input type="hidden" name="item_name" value="<?php echo $points .' shop points on '. $config['site_title']; ?>">
				<input type="hidden" name="item_number" value="1">
				<input type="hidden" name="amount" value="<?php echo $price; ?>">
				<input type="hidden" name="no_shipping" value="1">
				<input type="hidden" name="no_note" value="1">
				<input type="hidden" name="currency_code" value="<?php echo $paypal['currency']; ?>">
				<input type="hidden" name="lc" value="GB">
				<input type="hidden" name="bn" value="PP-BuyNowBF">
				<input type="hidden" name="return" value="<?php echo $paypal['success']; ?>">
				<input type="hidden" name="cancel_return" value="<?php echo $paypal['failed']; ?>">
				<input type="hidden" name="rm" value="2">
				<input type="hidden" name="notify_url" value="<?php echo $paypal['ipn']; ?>" />
				<input type="hidden" name="custom" value="<?php echo (int)$session_user_id; ?>">
				<input type="submit" value="  PURCHASE  ">
			</form>
		</td>
		<?php
		echo '</tr>';
		}
		?>
</table>
<?php } ?>

<?php
include 'layout/overall/footer.php'; ?>
