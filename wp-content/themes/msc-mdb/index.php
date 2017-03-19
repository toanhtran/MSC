
<?php get_header(); ?>

<!--Carousel Wrapper-->
<div id="carousel-example-1" class="carousel slide carousel-fade" data-ride="carousel">
    <!--Indicators-->
    <ol class="carousel-indicators">
        <li data-target="#carousel-example-1" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-1" data-slide-to="1"></li>
        <li data-target="#carousel-example-1" data-slide-to="2"></li>
    </ol>
    <!--/.Indicators-->

    <!--Slides-->
    <div class="carousel-inner" role="listbox">
        <!--First slide-->
        <div class="carousel-item active">
            <img src="https://mdbootstrap.com/images/slides/slide%20(7).jpg" alt="First slide">
        </div>
        <!--/First slide-->

        <!--Second slide-->
        <div class="carousel-item">
            <img src="https://mdbootstrap.com/images/slides/slide%20(8).jpg" alt="Second slide">
        </div>
        <!--/Second slide-->

        <!--Third slide-->
        <div class="carousel-item">
            <img src="https://mdbootstrap.com/images/slides/slide%20(9).jpg" alt="Third slide">
        </div>
        <!--/Third slide-->
    </div>
    <!--/.Slides-->

    <!--Controls-->
    <a class="left carousel-control" href="#carousel-examMple-1" role="button" data-slide="prev">
        <span class="icon-prev" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-1" role="button" data-slide="next">
        <span class="icon-next" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    <!--/.Controls-->
</div>
<!--/.Carousel Wrapper-->


<main>
<!--Main layout-->
<div class="container">
    <div class="row">
        <!--Main column-->
        <div class="col-md-8">
            <?php
            if ( have_posts() ) {
            while ( have_posts() ) {
            the_post();
            ?>
            <!--Post-->
            <div class="post-wrapper">
                <!--Post data-->
                <a href="<?php echo get_permalink() ?>"><h1 class="h1-responsive"><?php the_title(); ?></h1></a>
                <h5>Written by <a href=""><?php the_author(); ?></a>, <?php echo get_the_date(); ?></h5>
                <br>
                <!--Featured image -->
                <div class="view overlay hm-white-light z-depth-1-half">
                    <?php the_post_thumbnail( 'full', array( 'class'=> 'img-fluid z-depth-2')); ?>
                    <div class="mask">
                    </div>
                </div>
                <br>
                <!--Post excerpt-->
                <p><?php the_excerpt(); ?></p>
                <!--"Read more" button-->
                <a href="<?php echo get_permalink() ?>"><button class="btn btn-primary">Read more</button></a>
            </div>
            <!--/.Post-->
            <hr>
            <?php
            } // end while
            } // end if
            ?>
        </div>

        <!--Sidebar-->
        <div class="col-md-4">
            <?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
            <?php dynamic_sidebar( 'sidebar' ); ?>
            <?php endif; ?>
        </div>
        <!--/.Sidebar-->            
            
    </div>
</div>
<!--/.Main layout-->
</main>
            
<?php get_footer(); ?>

              