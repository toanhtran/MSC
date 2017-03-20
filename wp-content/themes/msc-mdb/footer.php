

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
                <!--/.First column-->

                <hr class="hidden-md-up">
                <!--Second column-->
                <div class="col-md-2">
                    <h5 class="title">First column</h5>
                    <?php
                    wp_nav_menu( array(
                    'menu'              => 'footer1',
                    'theme_location'    => 'footer1',
                    'depth'             => 1
                    )
                    );
                    ?>
                </div>
                <!--/.Second column-->     
                  

                <hr class="hidden-md-up">

                <!--Third column-->
                <div class="col-md-2">
                    <h5 class="title">Second column</h5>
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
                    <h5 class="title">Third column</h5>
                    <ul>
                        <li><a href="#!">Link 1</a></li>
                        <li><a href="#!">Link 2</a></li>
                        <li><a href="#!">Link 3</a></li>
                        <li><a href="#!">Link 4</a></li>
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