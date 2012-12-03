<table>

<?php foreach ($transactions as $transaction) : ?>
<tr>
	<?php foreach ($transaction as $key => $value) : ?>
	<td><?php echo $key . ': ' . $value; ?></td>
	<?php endforeach; ?>
</tr>
<?php endforeach; ?>

</table>
