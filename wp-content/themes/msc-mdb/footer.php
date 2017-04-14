

    <!--Footer-->
    <footer class="page-footer center-on-small-only primary-color-dark">

        <!--Footer Links-->
        <div class="container-fluid">
            <div class="row">

                <!--First column-->
                <div class="col-md-3 col-md-offset-1">
                    <h5 class="title">ABOUT MILSpouseCoders</h5>
                    <p>MILSpouseCoders is a non-profit organization aim at teaching military familes coding skills. </p>

                    <p>We our goal is to create a safe and fun community for learning computer skills.</p>
                </div>
                <!--First column-->
                <div class="col-md-3 col-md-offset-1">
                  <?php if ( is_active_sidebar( 'footer' ) ) : ?>
                  <?php dynamic_sidebar( 'footer' ); ?>
                  <?php endif; ?>
                </div>

            
                <!--/.Second column-->     
                  

                <hr class="hidden-md-up">

                <!--Third column-->
                <div class="col-md-2">
                    <h5 class="title">Resources We <i class="fa fa-heart" aria-hidden="true"></i></h5>
                    <ul>
                        <li><a href="#!">Link 1</a></li>
                        <li><a href="#!">Link 2</a></li>
                        <li><a href="#!">Link 3</a></li>
                        <li><a href="#!">Link 4</a></li>
                    </ul>
                </div>
                <!--/.Third column-->

                <hr class="hidden-md-up">

                <!--Fourth column-->
                <div class="col-md-2">
                    <h5 class="title">Let's Get Social</h5>
                    <ul>
                        <!--Facebook-->
                        <a href="https://www.facebook.com/MILSpouseCoders" class="icons-sm fb-ic"><i class="fa fa-facebook"> </i></a> &nbsp;
                        <!--Twitter-->
                        <a href="https://twitter.com/MILSpouseCoders" class="icons-sm tw-ic"><i class="fa fa-twitter"> </i></a> &nbsp;
                        <!--Google +-->
                        <a href="https://plus.google.com/115988512421332416550" class="icons-sm gplus-ic"><i class="fa fa-google-plus"> </i></a> &nbsp;
                                          
                        <a href="https://milspousecoders.slack.com/" class="icons-sm slack-ic"><i class="fa fa-slack"> </i></a> &nbsp;
                          <a class="icons-sm email-ic"><i class="fa fa-envelope-o"> </i></a> &nbsp;
                   
                    </ul>
                </div>
                <!--/.Fourth column-->

            </div>
        </div>
        <!--/.Footer Links-->

        <!--Copyright-->
        <div class="footer-copyright">
            <div class="container-fluid">
                © 2017 Copyright: <a href="http://milspousecoders.org/"> MILSpouseCoders.org </a>

            </div>
        </div>
        <!--/.Copyright-->

    </footer>
    <!--/.Footer-->
            
<?php wp_footer(); ?>

<script>
$("#mdb-navigation > ul > li").addClass("page-item")
$("#mdb-navigation > ul > li > a").addClass("page-link")
</script>
             
</body>
</html>