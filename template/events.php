<?php // Get RSS Feed(s)
ini_set('display_errors', false);
include_once(ABSPATH . WPINC . '/class-simplepie.php');
require_once dirname(__FILE__).'/../lib/simplepie-gcalendar.php';


$url = $_GET["feedurl"];
$email = $_GET["email"];
$show_past_events = $_GET["past"];
$sort_ascending = $_GET["asc"];
$order_by = $_GET["order"];
$expand_single_events = $_GET["expand"];
$language = $_GET["lang"];
$query = $_GET["query"];
$start = $_GET["start"];
$end = $_GET["end"];
$max = $_GET["max"];
$projection = $_GET["projection"];
$timezone = $_GET["tz"];

// Get a SimplePie feed object from the specified feed source.
$rss = new SimplePie_GCalendar();
// $rsss->set_show_past_events($show_past_events==1);
// $rss->set_sort_ascending($sort_ascending==1);
// $rss->set_orderby_by_start_date($order_by==1);
// $rss->set_expand_single_events($expand_single_events==1);
// $rss->set_max_events($max);
// $rss->set_cal_language($language);
// $rss->set_projection($projection);
// $rss->set_timezone($timezone);
// $rss->enable_cache(false);
// $rss->set_cache_duration(0);
// $rss->set_cal_query($query);

$rss->set_feed_url($feed);
$rss->set_max_events(3);
$rss->enable_order_by_date(FALSE);
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