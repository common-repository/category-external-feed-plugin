<?php

function widget_catfeed_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our little Google search form.
	function widget_catfeed($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_catfeed');
		$title = $options['title'];
		$buttontext = $options['buttontext'];

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.

		global $cat;

		if($feed = get_catfeed_feed($cat)) {

		  echo $before_widget . $before_title . $title . $after_title;
		  $url_parts = parse_url(get_bloginfo('home'));
		  
		  foreach($feed->get_items(0,10) as $item) :
		  ?>
		    <div>
		    <h4><a href="<?php echo $item->get_permalink() ?>"><?php echo $item->get_title() ?></a></h4>
		    </div>
		    <?php
		    endforeach;
		  echo $after_widget;
		} else {
		  echo $before_widget . $before_title . $title . $after_title;
		  echo '<div><small><a href="http://www.smartango.com/articles/wordpress-category-external-feed-plugin">wordpress external feed plugin</a></small></div>';
		  echo $after_widget;
		}
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_catfeed_control() {

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_catfeed');
		if ( !is_array($options) )
			$options = array('title'=>'', 'buttontext'=>__('Google Search', 'widgets'));
		if ( $_POST['catfeed-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['catfeed-title']));
			update_option('widget_catfeed', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$buttontext = htmlspecialchars($options['buttontext'], ENT_QUOTES);
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="catfeed-title">' . __('Title:') . ' <input style="width: 200px;" id="catfeed-title" name="catfeed-title" type="text" value="'.$title.'" /></label></p>';
		echo '<input type="hidden" id="catfeed-submit" name="catfeed-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Category outer feed', 'widgets'), 'widget_catfeed');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Category outer feed', 'widgets'), 'widget_catfeed_control', 300, 100);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_catfeed_init');
