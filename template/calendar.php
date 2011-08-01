<div id="calendar"></div>
<script type="text/javascript" src="<?php echo $this->getUrl(); ?>/public/plugins/fullcalendar/jquery/ui.core.js"></script>
<script type="text/javascript" src="<?php echo $this->getUrl(); ?>/public/plugins/fullcalendar/jquery/ui.draggable.js"></script>
<script type="text/javascript" src="<?php echo $this->getUrl(); ?>/public/plugins/fullcalendar/jquery/ui.resizable.js"></script>
<script type="text/javascript" src="<?php echo $this->getUrl(); ?>/public/plugins/fullcalendar/fullcalendar.js"></script>
<script type="text/javascript" src="<?php echo $this->getUrl(); ?>/public/plugins/fullcalendar/gcal.js"></script>
<script type="text/javascript">
  $(function() {
    $('#calendar').fullCalendar({
      contentHeight: 154,
			showNumbers : false,
      events: $.fullCalendar.gcalFeed('<?php echo $feed;?>'),
      eventClick: function(event) {
        // opens events in a popup window
        window.open(event.url, 'gcalevent', 'width=700,height=600');
        return false;
      },
    });
  });
</script>



