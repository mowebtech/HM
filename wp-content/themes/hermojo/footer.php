<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
  <!--FOOTER-->
    <div id="footer">
      <div class="foot-top">
        <div class="foot-links">
          <ul style="margin-right:100px">
            <li><a href="about-us.html" title="About Us">About Us</a></li>
            <li><a href="#" title="Terms and Conditions">Terms &amp; Conditions</a></li>
            <li><a href="#" title="Privacy Policy">Privacy Policy</a></li>
            <li><a href="#" title="Contact Us">Contact Us</a></li>
          </ul>
          <ul>
            <li><a href="#" title="Advertising">Advertising</a></li>
            <li><a href="#" title="Premium Member">Premium Member</a></li>
          </ul>
        </div>
        <div class="follow-us"> <span>Follow Us</span>
          <ul>
            <li><a href="#" title="Facebook"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-facebook.png" alt="Facebook" /></a></li>
            <li><a href="#" title="Twitter"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-twitter.png" alt="Twitter" /></a></li>
            <li><a href="#" title="Youtube"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon-youtube.png" alt="Youtube" /></a></li>
          </ul>
        </div>
        <!--NEWSLETTER-->
        <div class="newsletter-sp"> <span>Newsletter Signup</span>
          <form action="#" method="post">
            <input type="text" value="Enter Email Address" onblur="if(this.value == '') { this.value='Enter Email Address'}" onfocus="if (this.value == 'Enter Email Address') {this.value=''}" class="email-address" />
            <input type="submit" id="Subscribe" name="Subscribe" value="Subscribe" class="subscribe" />
            <br class="spacer" />
            <input type="checkbox" name="agree" value="Agree" />
            <span class="check-agree">Agree to Terms &amp; Conditions</span>
          </form>
        </div>
        <!--/NEWSLETTER-->
      </div>
      <div class="foot-botm">
        <p align="center" class="copyright">Copyright Womens Sport Network 2013: Reg. No. 07784934 - Registered in England &amp; Wales. Registered Office: St Aubyns House, leatherhead, Surrey, KT22 0LF</p>
      </div>
      <div class="foot-botm-1">
        <p align="center" class="powered">Powered by E10 Digital Limited</p>
      </div>
    </div>
    <!--/FOOTER-->
  </div>
  <!--/Wrapper Inner-->
</div>
</body>
</html>
