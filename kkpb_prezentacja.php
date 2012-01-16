<?php
global $wp_options;
$wp_options = get_option('kkpbsettings');

add_action('widgets_init', create_function('', 'return register_widget("kkprogressbarProjects");'));
class kkprogressbarProjects extends WP_Widget {

    function __construct() {
        // widget actual processes
        parent::WP_Widget('kkprogressbarProjects', 'KKProgressbar - Projects');
    }

    function form($instance) {
        // outputs the options form on admin

        $title = esc_attr($instance['title']);
?>
        <div><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></div>

<?php
    }

    function update($new_instance, $old_instance) {
        // processes widget options to be saved
        return $new_instance;
    }

    function widget($args, $instance) {
        // outputs the content of the widget
    	global $wp_options;

        $projects = kkpb::getAllProjects();
        
        $i = 0;

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        
        if($wp_options['project_info'] == 'on'){
        	$info = '<img src="'. WP_PLUGIN_URL .'/kkprogressbar/images/info-front.png" alt="Info" class="kkpb-proj-info" />';	
        	
        }else{
        	$info = '';
        }
        
        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

        if(isset($wp_options['wid_proj_height']) && ($wp_options['wid_proj_height'] != '')){
        	$proj_height = 'height: '.$wp_options['wid_proj_height'].'px !important;';
        }else{
        	$proj_height = 'height: 15px !important;';
        }
        
        foreach($projects as $project) {
        	$perc = floor(kkpb::getProjectPercentage($project->id));
        	
        	if($project->link != ''){
        		$nazwa = '<a href="'.$project->link.'">'.$project->nazwa.'</a>';
        	}else{
        		$nazwa = $project->nazwa;
        	}
        	
        	if($wp_options['project_perc'] == 'on'){
        		$perc_html = '<span class="kkpb-proj-perc">[ '.$perc.' % ]</span>';
        	}else{
        		$perc_html = '';
        	}
        	
        	$active = kkpb::isProjectActive($project->id);
			if($active){
	    		$wiadomosc = __(' - Work suspended.','lang-kkprogressbar');
	    	}else{
	    		$wiadomosc = '';
	    	}
        	
        	echo '<div class="kkpb-proj-box">';
        	echo '<div class="kkpb-proj-title">'.$info.' <span class="kkpb-proj-title-text">' . $nazwa . ' ' . $perc_html . '<span class="kkpb-proj-perc">'. $wiadomosc .'</span></span></div>';
        	
        	if($wp_options['global_progress'] == 'on'){
        		echo progressbar::drawProgressBar($perc, $active, $proj_height);
        	}
        	
        	echo '<div class="kkpb-proj-desc">'.stripslashes($project->opis).'</div></div>';
        }

        echo $after_widget;
    }

}

add_action('widgets_init', create_function('', 'return register_widget("kkprogressbar");'));
class kkprogressbar extends WP_Widget {

	function __construct() {
		// widget actual processes
		parent::WP_Widget('kkprogressbar', 'KKProgressbar - Projects with progressbars');
	}

	function form($instance) {
		// outputs the options form on admin

		$title = esc_attr($instance['title']);
		?>
        <div><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></div>

<?php
    }

    function update($new_instance, $old_instance) {
        // processes widget options to be saved
        return $new_instance;
    }

    function widget($args, $instance) {
        // outputs the content of the widget
    	global $wp_options;

        $projects = kkpb::getAllProjects();
        
        $i = 0;

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        
        if($wp_options['project_info'] == 'on'){
        	$info = '<img src="'. WP_PLUGIN_URL .'/kkprogressbar/images/info-front.png" alt="Info" class="kkpb-proj-info" />';	
        	
        }else{
        	$info = '';
        }
        
        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

        foreach($projects as $project) {
        	$perc = floor(kkpb::getProjectPercentage($project->id));
        	$progressbars = kkpb::getProjectProgressbars($project->id);
        	
        	
        	if($wp_options['project_perc'] == 'on'){
        		$perc_html = '<span class="kkpb-proj-perc">[ '.$perc.' % ]</span>';
        	}else{
        		$perc_html = '';
        	}
        	
        	if($project->link != ''){
        		$nazwa = '<a href="'.$project->link.'">'.$project->nazwa.'</a>';
        	}else{
        		$nazwa = $project->nazwa;
        	}
        	
        	if(isset($wp_options['wid_proj_height']) && ($wp_options['wid_proj_height'] != '')){
        		$proj_height = 'height: '.$wp_options['wid_proj_height'].'px !important;';
        	}else{
        		$proj_height = 'height: 15px !important;';
        	}
        	
        	if(isset($wp_options['wid_prog_height']) && ($wp_options['wid_prog_height'] != '')){
        		$short_height = 'height: '.$wp_options['wid_prog_height'].'px !important;';
        	}else{
        		$short_height = 'height: 12px !important;';
        	}
        	
			$active = kkpb::isProjectActive($project->id);
			if($active){
	    		$wiadomosc = __(' - Work suspended.','lang-kkprogressbar');
	    	}else{
	    		$wiadomosc = '';
	    	}
			
        	echo '<div class="kkpb-proj-box">';
        	echo '<div class="kkpb-proj-title">'.$info.' <span class="kkpb-proj-title-text">' . $nazwa . ' ' . $perc_html . '<span class="kkpb-proj-perc">'. $wiadomosc .'</span></span><a href="#" class="kkpb-rozwijarka kkpb-plus"></a></div>';
        	
        	if($wp_options['global_progress'] == 'on'){
        		echo progressbar::drawProgressBar($perc, $active, $proj_height);
        	}
        	
        	echo '<div class="kkpb-proj-prog-bar">';
        	foreach($progressbars as $progressbar){

        		if($progressbar->typ == 1){
        			$procent = $progressbar->procent;
        		}else if($progressbar->typ == 2){
        			$procent = ($progressbar->aktualna_wartosc / $progressbar->docelowa_wartosc) * 100;
        		}
        		
        		if($progressbar->status == 2){
        			$wstrzymany = true;
        			$wiadomosc = __(' - Work suspended.','lang-kkprogressbar');
        		}else{
        			$wstrzymany = false;
        			$wiadomosc = '';
        		}
        		        		
        		echo '<div class="kkpb-prog-bar-box"><div class="kkpb-prog-bar-box-title">'.$progressbar->nazwa.' - '.floor($procent).'%'.$wiadomosc.'</div>';
        		echo progressbar::drawProgressBar(floor($procent), $wstrzymany, $short_height);
        		echo '</div>';
        	}
        	echo '</div>';
        	
        	echo '<div class="kkpb-proj-desc">'.stripslashes($project->opis).'</div></div>';
        }

        echo $after_widget;
    }

}

class progressbar{
	
	public function drawProgressBar($perc, $wstrzymany, $height){
		global $wp_options;
		
		if($wstrzymany){
			
			$color = 'background-color: #ccc;';
			$texture = '';
			
		}else if($wp_options['use_gradient'] == 'color'){
			
			$color = 'background-color: #'.$wp_options['progress_color'] . ';';
			$texture = '';
			
			if($wp_options['use_texture'] == 'on'){
				$texture = $wp_options['progress_texture'];
			}
			
		}else if($wp_options['use_gradient'] == 'gradient'){
			
			$color = '';
			$texture = $wp_options['progress_gradient'];
			
		}
		
		if(isset($wp_options['progress_bg']) && ($wp_options['progress_bg'] != '')){
			$bg = $wp_options['progress_bg'];
		}else{
			$bg = 'kkpb-progressbar-content-dark';
		}
		
		if(isset($wp_options['border_color']) && ($wp_options['border_color'] != '')){
			$border_color = 'border-color: #'.$wp_options['border_color'].';';
		}else{
			$border_color = 'border-color: #000;';
		}
		
		if($perc > 100){
			$perc = 100;
		}
		
		$radius_bg = 'border-radius: ' .$wp_options['border_radius']. 'px; -webkit-border-radius: ' .$wp_options['border_radius']. 'px; -khtml-border-radius: ' .$wp_options['border_radius']. 'px; -moz-border-radius: ' .$wp_options['border_radius']. 'px;';
		$border_size = 'border-width: ' .$wp_options['border_size']. 'px; ';
		
		return '
		<div class="kkpb-progressbar-content '.$bg.'" title="" style="'.$radius_bg.' '.$border_size.' '.$border_color.'">
			<div class="kkpb-progress-bar '.$texture.' " style="'.$height.' width: '.$perc.'%; '.$color.' '.$radius_bg.'"></div>
		</div>
		';
	}
	
}


/* ========= SHORTCODEs =========== */

function kkpb_bartag_func($atts) {
    extract(shortcode_atts(array(
                'project' 	=> false,
    			'task'		=>	false,
    			'idproject'	=>	'1',
    			'idtask'	=>	'1'
                    ), $atts));

    global $wp_options;
    
    if($project && $project == 'true') {

        $pro = kkpb::getProject($idproject);

        if($wp_options['project_info'] == 'on'){
        	$info = '<img src="'. WP_PLUGIN_URL .'/kkprogressbar/images/info-front.png" alt="Info" class="kkpb-proj-info" />';

        }else{
            $info = '';
        }
        
        foreach($pro as $project) {
        	
        	$perc = floor(kkpb::getProjectPercentage($project->id));
        	
        	if($task && $task == 'true'){
        		$progressbars = kkpb::getProjectProgressbars($project->id);
        		$plus = '<a href="#" class="kkpb-rozwijarka kkpb-plus"></a>';
        	}else{
        		$progressbars = '';
        		$plus = '';
        	}
        	 
        	if($wp_options['project_perc'] == 'on'){
        		$perc_html = '<span class="kkpb-proj-perc">[ '.$perc.' % ]</span>';
        	}else{
        		$perc_html = '';
        	}
        	 
        	if($project->link != ''){
        		$nazwa = '<a href="'.$project->link.'">'.$project->nazwa.'</a>';
        	}else{
        		$nazwa = $project->nazwa;
        	}
        	 
        	if(isset($wp_options['short_proj_height']) && ($wp_options['short_proj_height'] != '')){
        		$proj_height = 'height: '.$wp_options['short_proj_height'].'px !important;';
        	}else{
        		$proj_height = 'height: 15px !important;';
        	}
        	 
        	if(isset($wp_options['short_prog_height']) && ($wp_options['short_prog_height'] != '')){
        		$short_height = 'height: '.$wp_options['short_prog_height'].'px !important;';
        	}else{
        		$short_height = 'height: 12px !important;';
        	}
        	
			$active = kkpb::isProjectActive($project->id);
			if($active){
	    		$wiadomosc = __(' - Work suspended.','lang-kkprogressbar');
	    	}else{
	    		$wiadomosc = '';
	    	}
			
        	$return = '<div class="kkpb-proj-box">';
        	$return .= '<div class="kkpb-proj-title">'.$info.' <span class="kkpb-proj-title-text">' . $nazwa . ' ' . $perc_html . '<span class="kkpb-proj-perc">' . $wiadomosc . '</span></span>'.$plus.'</div>';
        	
        	if($wp_options['global_progress'] == 'on'){
        		$return .= progressbar::drawProgressBar($perc, $active, $proj_height);
        	}
        	 
        	$return .= '<div class="kkpb-proj-prog-bar">';
        	
        	if($progressbars != ''){
	        	foreach($progressbars as $progressbar){
	        
	        		if($progressbar->typ == 1){
	        			$procent = $progressbar->procent;
	        		}else if($progressbar->typ == 2){
	        			$procent = ($progressbar->aktualna_wartosc / $progressbar->docelowa_wartosc) * 100;
	        		}
	        
	        		if($progressbar->status == 2){
	        			$wstrzymany = true;
	        			$wiadomosc = __(' - Work suspended.','lang-kkprogressbar');
	        		}else{
	        			$wstrzymany = false;
	        			$wiadomosc = '';
	        		}
	        
	        		$return .= '<div class="kkpb-prog-bar-box"><div class="kkpb-prog-bar-box-title">'.$progressbar->nazwa.' - '.floor($procent).'%'.$wiadomosc.'</div>';
	        		$return .= progressbar::drawProgressBar(floor($procent), $wstrzymany, $short_height);
	        		$return .= '</div>';
	        	}
        	}
        	
        	$return .= '</div>';
        	 
        	$return .= '<div class="kkpb-proj-desc">'.stripslashes($project->opis).'</div></div>';
        }
        
    }else{
    	$progressbars = kkpb::getProjectProgressbar($idtask);
    	
    	if(isset($wp_options['short_prog_height']) && ($wp_options['short_prog_height'] != '')){
    		$short_height = 'height: '.$wp_options['short_prog_height'].'px !important;';
    	}else{
    		$short_height = 'height: 12px !important;';
    	}
    	foreach ($progressbars as $progressbar){
	    	if($progressbar->typ == 1){
	    		$procent = $progressbar->procent;
	    	}else if($progressbar->typ == 2){
	    		$procent = ($progressbar->aktualna_wartosc / $progressbar->docelowa_wartosc) * 100;
	    	}
	    	
	    	if($progressbar->status == 2){
	    		$wstrzymany = true;
	    		$wiadomosc = __(' - Work suspended.','lang-kkprogressbar');
	    	}else{
	    		$wstrzymany = false;
	    		$wiadomosc = '';
	    	}
	    	
	    	$return = '<div class="kkpb-prog-bar-box" style="padding-left: 0; background: transparent; left: 0; margin: 10px 0;"><div class="kkpb-prog-bar-box-title">'.$progressbar->nazwa.' - '.floor($procent).'%'.$wiadomosc.'</div>';
	    	$return .= progressbar::drawProgressBar(floor($procent), $wstrzymany, $short_height);
	    	$return .= '</div>';
    	}
    }
	return $return;
}

add_shortcode('kkpb', 'kkpb_bartag_func');

/* =============================================================== */

function register_button($buttons) {
	array_push($buttons, "kkpb");
	return $buttons;
}

function add_plugin($plugin_array) {
	$plugin_array['kkpb'] = WP_PLUGIN_URL.'/kkprogressbar/js/kkpb-codes.js';
	return $plugin_array;
}

function add_button() {
	if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
	{
		add_filter('mce_external_plugins', 'add_plugin');
		add_filter('mce_buttons', 'register_button');
	}
}

add_action('init', 'add_button');
?>