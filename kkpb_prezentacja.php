<?php
add_action('widgets_init', create_function('', 'return register_widget("kkprogressbar");'));
add_shortcode('kkprogressbar', 'kkpb_bartag_func');

class kkprogressbar extends WP_Widget {

    function kkprogressbar() {
        // widget actual processes
        parent::WP_Widget(false, $name = 'KK Progress Bar');
    }

    function form($instance) {
        // outputs the options form on admin

        $title = esc_attr($instance['title']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<script type="text/javascript">
var image_a = new Image();
image_a.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/top-left.png';
var image_b = new Image();
image_b.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/top.png';
var image_c = new Image();
image_c.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/top-right.png';
var image_d = new Image();
image_d.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/left.png';
var image_e = new Image();
image_e.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/right.png';
var image_f = new Image();
image_f.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/background.png';
var image_g = new Image();
image_g.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/bottom-left.png';
var image_h = new Image();
image_h.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/bottom-right.png';
var image_i = new Image();
image_i.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/bottom.png';
var image_j = new Image();
image_j.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/bottom-shadow.png';
var image_k = new Image();
image_k.src = '<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/skins/<?php echo get_option('kkpb-cloud-skin') ?>/dziubek.png';
</script>
<?php
    }

    function update($new_instance, $old_instance) {
        // processes widget options to be saved
        return $new_instance;
    }

    function widget($args, $instance) {
        // outputs the content of the widget

        global $wpdb;
        $table_name = $wpdb->prefix . "kkprogressbar";

        $sql = "SELECT * FROM $table_name";
        $wyniki = $wpdb->get_results($sql, ARRAY_A);

        
        $i = 0;

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

        foreach ($wyniki as $wynik) {

            if ($wynik['status'] == 1 || $wynik['status'] == 2) {

                if ($wynik['status'] == 1) {

                    if (get_option('kkpb-textura') == 1) {
                        $klasa = "background: #" . get_option('kkpb-kol-aktywny') . " url(" . WP_PLUGIN_URL . "/kkprogressbar/images/textura_a.png) repeat-x center center;";
                        $prace = '';
                    } else {
                        $klasa = "background: #" . get_option('kkpb-kol-aktywny') . ";";
                        $prace = '';
                    }
                } else if ($wynik['status'] == 2) {

                    if (get_option('kkpb-textura') == 1) {
                        $klasa = "background: #" . get_option('kkpb-kol-nieaktywny') . " url(" . WP_PLUGIN_URL . "/kkprogressbar/images/textura_a.png) repeat-x center center;";
                        $prace = __('Works suspended', 'lang-kkprogressbar') . " - ";
                    } else {
                        $klasa = "background: #" . get_option('kkpb-kol-nieaktywny') . ";";
                        $prace = __('Works suspended', 'lang-kkprogressbar') . " - ";
                    }
                }
                if ($wynik['typ'] == 2) {
                    $head =  "<span class='kkpb-head-project'>" . __('Project', 'lang-kkprogressbar') . ":</span> <strong style=\"font-size:12px;\">" . $wynik['nazwa'] . "</strong>";
                } else if ($wynik['typ'] == 1) {
                    $table_posts = $wpdb->prefix . "posts";
                    $row = $wpdb->get_row("SELECT post_title FROM $table_posts WHERE ID = '$wynik[id]'", ARRAY_A);
                    $head = "<span class='kkpb-head-project'>" . __('Article', 'lang-kkprogressbar') . ":</span> <strong style=\"font-size:12px;\">" . $row['post_title'] . "</strong>";
                }

                echo '
                    <div style="margin-bottom: 15px; position: relative; overflow: visible;" class="kkpb-row">
                        <div style="font-size:10px;">' . $head . '</div>
                        <div style="margin: 5px 0px; border: 1px #ccc solid; height: 10px; -webkit-border-radius: 4px; -khtml-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
                            <div style="' . $klasa . ' height: 10px; width:' . $wynik['procent'] . '%; -webkit-border-radius: 4px; -khtml-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;"></div>
                        </div>
                        <div style="text-align:right; font-size:10px;">' . $prace . ' <strong style="font-size:12px;">' . $wynik['procent'] . '%</strong> <span class="kkpb-foot-done">' . __('done', 'lang-kkprogressbar') . '.</span></div>
                    	';
                if(get_option('kkpb-cloud') == 1){
                echo '
                        <div class="div-cloud" style="position: absolute; display: none; bottom: 0px;">
                        	<table style="border-spacing: 0px; border-collapse: collapse; color: #'.get_option('kkpb-kol-cloud').'; padding: 0; margin: 0; width: ' . get_option('kkpb-cloud-width') . 'px;">
					        	<tr>
					        		<td style="padding: 0; margin: 0; width: 26px; height: 26px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/top-left.png) no-repeat;"></td>
					        		<td style="padding: 0; margin: 0;; height: 26px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/top.png) repeat-x;"></td>
					        		<td style="padding: 0; margin: 0; width: 26px; height: 26px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/top-right.png) no-repeat;"></td>
					        	</tr>
					        	<tr>
					        		<td style="padding: 0; margin: 0; width: 26px; background: url('. WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/left.png) repeat-y;"></td>
					        		<td style="padding: 0; margin: 0; font-size: 11px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/background.png);">
					        			'.$wynik['opis'].'
					        		</td>
					        		<td style="padding: 0; margin: 0; width: 26px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/right.png) repeat-y;"></td>
					        	</tr>
					        	<tr>
					        		<td rowspan="2" style="padding: 0; margin: 0; width: 26px; height: 37px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/bottom-left.png) no-repeat;"></td>
					        		<td style="padding: 0; margin: 0; height: 16px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/bottom.png) repeat-x;"></td>
					        		<td rowspan="2" style="padding: 0; margin: 0; width: 26px; height: 37px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/bottom-right.png) no-repeat;"></td>
					        	</tr>
					        	<tr>
					        		<td style="vertical-align:top; padding: 0; margin: 0; text-align: center; height: 21px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/bottom-shadow.png) repeat-x;"><img src="'.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/dziubek.png" alt="" /></td>
					        	</tr>
					        </table>
                        </div>';
                }  
                echo '    </div>
                    ';
            }
        }

        echo $after_widget;
    }

}

function kkpb_bartag_func($atts) {
    extract(shortcode_atts(array(
                'idkkpb' => 'noid',
                    ), $atts));

    if ($idkkpb != '' && $idkkpb != 'noid') {
        global $wpdb;
        $table_name = $wpdb->prefix . "kkprogressbar";

        $sql = "SELECT * FROM $table_name WHERE id = '$idkkpb'";
        $wynik = $wpdb->get_row($sql, ARRAY_A);

        $i = 0;

        if ($wynik['status'] == 1 || $wynik['status'] == 2) {

            if ($wynik['status'] == 1) {

                if (get_option('kkpb-textura') == 1) {
                    $klasa = "background: #" . get_option('kkpb-kol-aktywny') . " url(" . WP_PLUGIN_URL . "/kkprogressbar/images/textura_a.png) repeat-x center center;";
                    $prace = '';
                } else {
                    $klasa = "background: #" . get_option('kkpb-kol-aktywny') . ";";
                    $prace = '';
                }
            } else if ($wynik['status'] == 2) {


                if (get_option('kkpb-textura') == 1) {
                    $klasa = "background: #" . get_option('kkpb-kol-nieaktywny') . " url(" . WP_PLUGIN_URL . "/kkprogressbar/images/textura_a.png) repeat-x center center;";
                    $prace = __('Works suspended', 'lang-kkprogressbar') . " - ";
                } else {
                    $klasa = "background: #" . get_option('kkpb-kol-nieaktywny') . ";";
                    $prace = __('Works suspended', 'lang-kkprogressbar') . " - ";
                }
            }
            if ($wynik['typ'] == 2) {
                $head = "<span class='kkpb-head-project'>" . __('Project', 'lang-kkprogressbar') . ":</span> <strong style=\"font-size:12px;\">" . $wynik['nazwa'] . "</strong>";
            } else if ($wynik['typ'] == 1) {
                $table_posts = $wpdb->prefix . "posts";
                $row = $wpdb->get_row("SELECT post_title FROM $table_posts WHERE ID = '$wynik[id]'", ARRAY_A);
                $head = "<span class='kkpb-head-project'>" . __('Article', 'lang-kkprogressbar') . ":</span> <strong style=\"font-size:12px;\">" . $row['post_title'] . "</strong>";
            }

            echo '
            <div style="margin-bottom: 15px; position: relative; overflow: visible;" class="kkpb-row-tab">
                        <div style="margin: 5px 0px; border: 1px #ccc solid; height: 20px; -webkit-border-radius: 4px; -khtml-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
                            <div style="' . $klasa . ' height: 20px; width:' . $wynik['procent'] . '%; -webkit-border-radius: 4px; -khtml-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;"></div>
                        </div>
                        <div style="text-align:right; font-size:10px;">' . $prace . ' <strong style="font-size:12px;">' . $wynik['procent'] . '%</strong> <span class="kkpb-foot-done">' . __('done', 'lang-kkprogressbar') . '.</span></div>
			';
            if(get_option('kkpb-cloud') == 1){
              echo '          
                        <div class="div-cloud" style="position: absolute; display: none; bottom: 0px;">
                        	<table style="border-spacing: 0px; border-collapse: collapse; color: #'.get_option('kkpb-kol-cloud').'; padding: 0; margin: 0; width: ' . get_option('kkpb-cloud-width') . 'px;">
					        	<tr>
					        		<td style="padding: 0; margin: 0; width: 26px; height: 26px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/top-left.png) no-repeat;"></td>
					        		<td style="padding: 0; margin: 0;; height: 26px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/top.png) repeat-x;"></td>
					        		<td style="padding: 0; margin: 0; width: 26px; height: 26px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/top-right.png) no-repeat;"></td>
					        	</tr>
					        	<tr>
					        		<td style="padding: 0; margin: 0; width: 26px; background: url('. WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/left.png) repeat-y;"></td>
					        		<td style="padding: 0; margin: 0; font-size: 11px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/background.png);">
					        			'.$wynik['opis'].'
					        		</td>
					        		<td style="padding: 0; margin: 0; width: 26px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/right.png) repeat-y;"></td>
					        	</tr>
					        	<tr>
					        		<td rowspan="2" style="padding: 0; margin: 0; width: 26px; height: 37px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/bottom-left.png) no-repeat;"></td>
					        		<td style="padding: 0; margin: 0; height: 16px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/bottom.png) repeat-x;"></td>
					        		<td rowspan="2" style="padding: 0; margin: 0; width: 26px; height: 37px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/bottom-right.png) no-repeat;"></td>
					        	</tr>
					        	<tr>
					        		<td style="vertical-align:top; padding: 0; margin: 0; text-align: center; height: 21px; background: url('.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/bottom-shadow.png) repeat-x;"><img src="'.WP_PLUGIN_URL.'/kkprogressbar/skins/' . get_option('kkpb-cloud-skin') . '/dziubek.png" alt="" /></td>
					        	</tr>
					        </table>
                        </div>';
            }
              echo '
           </div>
            ';
        }
    }
}
?>