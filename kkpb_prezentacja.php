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
        <?php

    }

    function update($new_instance, $old_instance) {
        // processes widget options to be saved
        return $new_instance;
    }

    function widget($args, $instance) {
        // outputs the content of the widget

        global $wpdb;
        $table_name = $wpdb->prefix."kkprogressbar";

        $sql = "SELECT * FROM $table_name";
        $wyniki = $wpdb->get_results($sql,ARRAY_A);

        $i = 0;

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

        foreach ($wyniki as $wynik) {

            if($wynik['status'] == 1 || $wynik['status'] == 2) {

                if($wynik['status'] == 1) {

                    $klasa = "background: #008add;";
                    $prace = '';

                }else if($wynik['status'] == 2) {

                    $klasa = "background: #ccc;";
                    $prace = '';

                }
                if($wynik['typ'] == 2) {
                    $head = "Projekt: <strong style=\"font-size:12px;\">".$wynik['nazwa']."</strong>";
                }else if($wynik['typ'] == 1) {
                    $table_posts = $wpdb->prefix . "posts";
                    $row = $wpdb->get_row("SELECT post_title FROM $table_posts WHERE ID = '$wynik[id]'", ARRAY_A);
                    $head = "Artykuł: <strong style=\"font-size:12px;\">".$row['post_title']."</strong>";
                }

                echo '
                    <div style="margin-bottom: 15px;">
                        <div style="font-size:10px;">'.$head.'</div>
                        <div style="margin: 5px 0px; border: 1px #ccc solid; height: 10px; -webkit-border-radius: 4px; -khtml-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
                            <div style="'.$klasa.' height: 10px; width:'.$wynik['procent'].'%; -webkit-border-radius: 4px; -khtml-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;"></div>
                        </div>
                        <div style="text-align:right; font-size:10px;">'.$prace.' <strong style="font-size:12px;">'.$wynik['procent'].'%</strong> done!</div>
                    </div>
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

    if($idkkpb != '' && $idkkpb != 'noid') {
        global $wpdb;
        $table_name = $wpdb->prefix."kkprogressbar";

        $sql = "SELECT * FROM $table_name WHERE id = '$idkkpb'";
        $wynik = $wpdb->get_row($sql,ARRAY_A);
        $i = 0;

        if($wynik['status'] == 1 || $wynik['status'] == 2) {

            if($wynik['status'] == 1) {

                $klasa = "background: #008add;";
                $prace = '';

            }else if($wynik['status'] == 2) {

                $klasa = "background: #ccc;";
                $prace = '';

            }
            if($wynik['typ'] == 2) {
                $head = "Projekt: <strong style=\"font-size:12px;\">".$wynik['nazwa']."</strong>";
            }else if($wynik['typ'] == 1) {
                $table_posts = $wpdb->prefix . "posts";
                $row = $wpdb->get_row("SELECT post_title FROM $table_posts WHERE ID = '$wynik[id]'", ARRAY_A);
                $head = "Artykuł: <strong style=\"font-size:12px;\">".$row['post_title']."</strong>";
            }

          return '
            <div style="margin-bottom: 15px;">
                        <div style="margin: 5px 0px; border: 1px #ccc solid; height: 20px; -webkit-border-radius: 4px; -khtml-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;">
                            <div style="'.$klasa.' height: 20px; width:'.$wynik['procent'].'%; -webkit-border-radius: 4px; -khtml-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;"></div>
                        </div>
                        <div style="text-align:right; font-size:10px;">'.$prace.' <strong style="font-size:12px;">'.$wynik['procent'].'%</strong> done!</div>
                    </div>

            ';

        }

    }

}

?>
