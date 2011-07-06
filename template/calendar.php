<?php if(!isset($hide_upcoming)):?>

<?php // Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/feed.php');

// Get a SimplePie feed object from the specified feed source.
$rss = fetch_feed($feed);
if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly
    // Figure out how many total items there are, but limit it to 5.
    $maxitems = $rss->get_item_quantity(isset($limit) ?$limit:5);

    // Build an array of all the items, starting with element 0 (first element).
    $rss_items = $rss->get_items(0, $maxitems);
endif;
?>
<ul>
    <?php if ($maxitems == 0) echo '<li>No items.</li>';
    else
    // Loop through each feed item and display each item as a hyperlink.
    foreach ( $rss_items as $item ) : ?>
    <li>
        <a href='<?php echo $item->get_permalink(); ?>' title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>' target="_blank">
        <?php echo $item->get_title(); ?></a>
    </li>
    <?php endforeach; ?>
</ul>
<br /><br />
<?php endif;?>

<?php if(!isset($hide_calendar)):?>
<div id='calendar'></div>
<script type="text/javascript" src="<?php echo bloginfo('siteurl') . $this -> PLUGINURL; ?>/public/plugins/fullcalendar/jquery/jquery.js"></script>
<script type="text/javascript" src="<?php echo bloginfo('siteurl') . $this -> PLUGINURL; ?>/public/plugins/fullcalendar/jquery/ui.core.js"></script>
<script type="text/javascript" src="<?php echo bloginfo('siteurl') . $this -> PLUGINURL; ?>/public/plugins/fullcalendar/jquery/ui.draggable.js"></script>
<script type="text/javascript" src="<?php echo bloginfo('siteurl') . $this -> PLUGINURL; ?>/public/plugins/fullcalendar/jquery/ui.resizable.js"></script>
<script type="text/javascript" src="<?php echo bloginfo('siteurl') . $this -> PLUGINURL; ?>/public/plugins/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="<?php echo bloginfo('siteurl') . $this -> PLUGINURL; ?>/public/plugins/fullcalendar/gcal.js"></script>
<script type="text/javascript">
  $(function() {
    $('#calendar').fullCalendar({
      events: $.fullCalendar.gcalFeed('<?php echo $feed;?>'),
      eventClick: function(event) {
        // opens events in a popup window
        window.open(event.url, 'gcalevent', 'width=700,height=600');
        return false;
      },
      loading: function(bool) {
        if (bool) {
          $('#loading').show();
        }else{
          $('#loading').hide();
        }
      }
    });
  });
</script>
<?php endif;?>



