<?php

if ($friends) {
?>

<table>
	<caption>There are <?php echo count($friends); ?> results. Showing 10 of YOUR FRIENDS who have used <a href='http://unstablecode.com/travel'>TRAVEL WITH FRIENDS</a></caption>
	<thead>
		<tr>
			<th></th>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$ctr = 0;
		foreach ($friends as $f) {
			if ($ctr > 9) {
				break;
			}
	?>
		<tr>
			<td><img src="<?php echo $f->getImageUrl(); ?>" /></td>
			<td><?php echo $f->getName(); ?></td>
		</tr>
	<?php
			++$ctr;
		}
	?>
	</tbody>
</table>

<?php
} else {
?>
<h2>Nothing to see here.</h2>
<?php
}
?>

