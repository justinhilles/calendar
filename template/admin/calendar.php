<h1>Calendar</h1>
<div class="wrap">
	<table class="widefat post fixed" cellspacing="0">
		<thead>
			<tr>
				<th width="20">ID</th>
				<th>Title</th>
				<th>Start</th>
				<th>End</th>
				<th>Action</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th width="20">ID</th>
				<th>Title</th>
				<th>Start</th>
				<th>End</th>
				<th>Action</th>
			</tr>
		</tfoot>
		<tbody>
			<?php if(isset($this->events)): foreach($this->events as $event):?>
				<tr>
					<td><?php echo $event->ID; ?></td>
					<td><?php echo $event->title;?></td>
					<td><?php echo $event->begin; ?></td>
					<td><?php echo $event->end; ?></td>
					<td>
						<a href="<?php echo admin_url('admin.php?page=edit-event&id=' . $event->ID);?>">Edit</a> / 
						<a href="<?php echo admin_url('admin.php?action=delete-event&id=' . $event->ID . '&_wpnonce=' . $nonce);?>">Delete</a>
					</td>
				</tr>	
			<?php endforeach; endif; ?>
		</tbody>
	</table>