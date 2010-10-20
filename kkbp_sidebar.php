
            <div class="postbox gdrgrid frontright">
                <h3 class="hndle" style="cursor:default;"><span>Info:</span></h3>
                <div class="inside">
                    <div style="margin: 10px 15px;">
                        <div style="margin: 10px 0px;"><span class="kkc-small-text"><strong><?php echo __('Author', 'lang-kkprogressbar'); ?>:</strong></span> <br /><a href="http://krzysztof-furtak.pl" target="_blank" >Krzysztof Furtak</a> <span class="kkc-small-text">Web Developer</span></div>
                        <div style="margin: 10px 0px;"><span class="kkc-small-text"><strong><?php echo __('Report bug', 'lang-kkprogressbar'); ?>:</strong></span><br /> <a href="http://krzysztof-furtak.pl/2010/06/wp-kk-progressbar-plugin/" target="_blank" ><?php echo __('Plugin site', 'lang-kkprogressbar'); ?></a></div>
                        <hr />
                        <div style="margin: 10px 0px; font-size: 10px;">
                            <h4><?php echo __('Legend', 'lang-kkprogressbar'); ?>:</h4>
                            <img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/aktywny.png" alt="Yes" style="vertical-align:middle;" /> - <?php echo __('Active', 'lang-kkprogressbar'); ?><br />
                            <img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/wstrzymany.png" alt="Yes" style="vertical-align:middle;" /> - <?php echo __('Works suspended', 'lang-kkprogressbar'); ?><br />
                            <img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/nieaktywny.png" alt="Yes" style="vertical-align:middle;" /> - <?php echo __('Inactive (not displayed)', 'lang-kkprogressbar'); ?><br />
                        </div>
                    </div>
                </div>
            </div>

            <div class="postbox gdrgrid frontright">
                <h3 class="hndle" style="cursor:default;"><span><?php echo __('Translations', 'lang-kkprogressbar'); ?>:</span></h3>
                <div class="inside">
                    <div style="margin: 10px 15px;">
                            
                            <p class="kkpb-small-text"><?php echo __('People who helped in plugin translation', 'lang-kkprogressbar'); ?>:</p>
							<hr />
							<table>
                            <tr><td><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/usa.gif" alt="USA/Eng" style="vertical-align:middle;" /> -</td><td> Paulina Urbaniak</td></tr>
                            <tr><td><img src="<?php echo WP_PLUGIN_URL; ?>/kkprogressbar/images/pl.gif" alt="PL" style="vertical-align:middle;" /> -</td><td> <a href="http://krzysztof-furtak.pl/" target="_blank">Krzysztof Furtak</a></td></tr>
                            </table>
							<hr />
							<p class="kkpb-small-text"><?php echo __('Thank you', 'lang-kkprogressbar'); ?>!</p>
							
                        </div>
                    </div>
                </div>
            <!-- 
            <div class="postbox gdrgrid frontright">
                <h3 class="hndle" style="cursor:default;"><span><?php echo __('Donation', 'lang-kkprogressbar'); ?>:</span></h3>
                <div class="inside">
                    <div style="margin: 10px 15px;">
                            
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
                            ">
                            <input type="image" src="http://krzysztof-furtak.pl/upload/buy_coffee.png" border="0" name="submit" alt="Buy me a coffee.">
                            <img alt="" border="0" src="https://www.paypal.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
                            </form>


                        </div>
                    </div>
                </div>
              -->
            
         