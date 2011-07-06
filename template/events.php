<?php // Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/feed.php');

// Get a SimplePie feed object from the specified feed source.
$rss = fetch_feed($feed);
if (!is_wp_error($rss)):
  if($items = $rss->get_items(0, $rss->get_item_quantity(isset($limit) ? $limit : 5))):?>
    <ul<?php echo isset($menu_class) ? ' class="'.$menu_class.'"':null;?> >
      <?php foreach($items as $item): ?>
        <li>
          <div class="post">
            <div class="date">
            <ul>
              <li class="day"><?php echo $item->get_date('l');?></li>
              <li class="month"><?php echo $item->get_date('m');?></li>
              <li class="number"><?php echo $item->get_date('d');?></li>
            </ul>
            </div>
            <div class="description">
            <h3><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h3>
            <p>The Little Italy Association (LIA) and a small group of community members organized a festival that celebrates Italian culture not only in the entertainment provided, on the one stage, but the types of vendors that were allowed to display their wares along India Street.</p>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif;?>
<?php endif;?>