<script type="text/javascript">
	$(function() {
	    $("#timebegin").kmTimepicker();
			$('#datebegin').datepicker();
			$("#timeend").kmTimepicker();
			$('#dateend').datepicker();
	});
</script>

<style type="text/css">
	.datetime {
		width: 400px;
		display: block;
		float: left;
	}
</style>

<form action="" method="post">
	<?php wp_nonce_field($view); ?>
	<div id="poststuff" class="postbox">
		<div class="inside">
			<p>
				<label for="data[begin]">Begin Date/Time</label><br />
				<input type="text" name="datebegin" id="datebegin" class="date" value="<?php echo $this -> event -> datebegin;?>">
				<input type="text" name="timebegin" id="timebegin" class="time" value="<?php echo $this -> event -> timebegin;?>" >
			</p>
			<p>
				<label for="data[end]">End Date/Time</label><br />
				<input type="text" name="dateend" id="dateend" class="date" value="<?php echo $this -> event -> dateend;?>">
				<input type="text" name="timeend" id="timeend" class="time" value="<?php echo $this -> event -> timeend;?>" >
			</p>
			<p>
				<label for="data[title]">Title</label><br />
				<input type="text" name="data[title]" value="<?php echo $this ->event->title;?>"  />
			</p>
			<p>
				<label for="data[desc]">Description</label><br />
				<textarea name="data[desc]"><?php echo $this ->event->desc;?></textarea>
			</p>
			<p>
				<label for="data[category_id]">Category</label><br />
				<select name="data[category_id]">
				<?php	$category_ids = get_all_category_ids();
				echo "<option value=''></option>";
					foreach($category_ids as $cat_id) {
					  $cat_name = get_cat_name($cat_id);
					  echo "<option value=\"".  $cat_id . '\">(' . $cat_id . ') ' . $cat_name . '</option>';
					}?>	
				</select>
			</p>
		</div>
	</div>
	<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
</form>