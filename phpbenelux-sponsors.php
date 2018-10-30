<?php
/*
Plugin Name: PHPBenelux Sponsors
Version: 1.0
Plugin URI: http://phpbenelux.eu
Description: Sponsor plugin 2017
Author: Martin de Keijzer
Author URI: http://phpbenelux.eu/
*/


/**
 * Remove bs- css class prefixes to enable bootstrap horizontal layout
 */
add_action( 'widgets_init', 'phpbenelux_sponsors_widget_init' );

function phpbenelux_sponsors_widget_init() {
    register_widget( 'phpbenelux_sponsors_widget' );
}

class phpbenelux_sponsors_widget extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'phpbenelux_sponsors_widget',
            'description' => 'Shows the sponsors for PHPBenelux Conference'
        );

        parent::__construct( 'phpbenelux_sponsors_widget', 'PHPBenelux sponsors Widget', $widget_details );

    }

    public function form( $instance ) {
        // Backend Form
    }

    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }

    public function widget( $args, $instance ) {
//        if (!isset($_GET['feat'])) {
//            return;
//        }
        ?>
        <div class="bs-row sponsors-widget">
            <div class="bs-col-md-5">
                <header class="post-heading">
                    <div class="post-title-wrapper">
                        <h2 class="post-title">Platinum</h2>
                    </div>
                </header>
                <?php echo $this->renderSponsors('platinum', 'bs-col-md-12'); ?>
            </div>
            <div class="bs-col-md-7">
                <div class="bs-row">
                    <header class="post-heading">
                        <div class="post-title-wrapper">
                            <h2 class="post-title">Gold</h2>
                        </div>
                    </header>
                    <?php echo $this->renderSponsors('gold', 'bs-col-md-4'); ?>
                </div>
                <div class="bs-row">
                    <header class="post-heading">
                        <div class="post-title-wrapper">
                            <h2 class="post-title">Silver</h2>
                        </div>
                    </header>
                    <?php echo $this->renderSponsors('silver', 'bs-col-md-3'); ?>
                </div>
            </div>
            <div class="bs-col-md-12">
                <header class="post-heading">
                    <div class="post-title-wrapper">
                        <h2 class="post-title">Custom</h2>
                    </div>
                </header>
                <?php echo $this->renderCustomSponsors('bs-col-md-4'); ?>
            </div>
        </div>
        <?php
    }

    public function renderCustomSponsors($colValue) {
        $args = array(
            'numberposts'	=> -1,
            'post_type'		=> 'sponsor',
            'meta_query'	=> array (
                array (
                    'key'   => 'sponsor_type',
                    'value' => array('platinum', 'gold', 'silver'),
                    'compare' => 'NOT IN',
                )
            ),
        );
        $the_query = new WP_Query( $args );

        $this->renderQueryResults($the_query, 'custom', $colValue, true);
    }

    public function renderSponsors($type, $colValue, $withlabel = false) {
        $args = array(
            'numberposts'	=> -1,
            'post_type'		=> 'sponsor',
            'meta_key'		=> 'sponsor_type',
            'meta_value'	=> $type
        );
        $the_query = new WP_Query( $args );

        $this->renderQueryResults($the_query, $type, $colValue, $withlabel);
    }

    protected function renderQueryResults(WP_Query $the_query, $type , $colValue, $withLabel)
    {
        if ($the_query->have_posts()) {
            ?><div class="bs-row <?=$type?>-logos"><?php
            while ($the_query->have_posts()) {
                $the_query->the_post();
                ?>

                <a href="<?php the_permalink(); ?>">
                    <div class="<?=$colValue?>">
                        <div class="sponsorlogo">
                            <?php
                            if ( has_post_thumbnail() ) {
                                ?>
                                <img src="<?php the_post_thumbnail_url('medium_large'); ?>" />
                                <?php
                            } else {
                                the_title();
                            }
                            ?>
                            <?php if($withLabel === true) {
                                ?>
                                <div class="sponsor-label">
                                    <?php echo get_field('sponsor_type'); ?>
                                </div>
                                <?php
                            } ?>
                        </div>
                    </div>
                </a>
                <?php
            }
            ?></div><?php
        }
        wp_reset_query();
    }
}