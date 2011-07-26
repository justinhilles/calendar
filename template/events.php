<?php // Get RSS Feed(s)
ini_set('display_errors', false);
include_once(ABSPATH . WPINC . '/class-simplepie.php');
require_once dirname(__FILE__).'/../lib/simplepie-gcalendar.php';

// Get a SimplePie feed object from the specified feed source.
$rss = new SimplePie_GCalendar();
$rss->set_feed_url($feed);
$rss->set_max_events($limit);
$rss->init();

$rss->handle_content_type();
  if($items = $rss->get_items()):?>
    <ul<?php echo isset($menu_class) ? ' class="'.$menu_class.'"':null;?> >
      <?php foreach($items as $item): ?>
        <li>
          <div class="post">
            <div class="date">
            <ul>
              <li class="day"><?php echo date('l', $item->get_start_date());?></li>
              <li class="month"><?php echo date('m', $item->get_start_date());?></li>
              <li class="number"><?php echo date('d', $item->get_start_date());?></li>
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
<?php ini_set('display_errors', true);?>