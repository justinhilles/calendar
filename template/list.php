<?php // Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/feed.php');

// Get a SimplePie feed object from the specified feed source.
$rss = fetch_feed($feed);
if (!is_wp_error($rss)):
  if($items = $rss->get_items(0, $rss->get_item_quantity(isset($limit) ? $limit : 5))):?>
    <ul<?php echo isset($menu_class) ? ' class="'.$menu_class.'"':null;?> >
      <?php foreach($items as $item): ?>
        <li>
            <a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif;?>
<?php endif;?>