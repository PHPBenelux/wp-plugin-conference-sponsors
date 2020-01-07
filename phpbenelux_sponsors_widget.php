<?php
/*
Plugin Name: PHPBenelux Sponsors
Version: 1.0
Plugin URI: http://phpbenelux.eu
Description: Sponsor plugin
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
	/**
	 * phpbenelux_sponsors_widget constructor.
	 */
    public function __construct()
    {
        $widget_details = array(
            'classname' => 'phpbenelux_sponsors_widget',
            'description' => 'Shows the sponsors for PHPBenelux Conference'
        );

        parent::__construct( 'phpbenelux_sponsors_widget', 'PHPBenelux sponsors Widget', $widget_details );

    }

	/**
	 * FunctionDescription
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
    public function form( $instance ) {
        // Backend Form
    }

	/**
	 * FunctionDescription
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }

	/**
	 * FunctionDescription
	 *
	 * @param array $args
	 * @param array $instance
	 */
    public function widget( $args, $instance ) {
        ?>
        <div class="bs-row sponsors-widget">
            <div class="bs-col-md-5">
                <?php echo $this->renderSponsors('platinum', 'bs-col-md-12', false, 'Platinum'); ?>
            </div>
            <div class="bs-col-md-7">
                <div class="bs-row">
                    <?php echo $this->renderSponsors('gold', 'bs-col-md-4', false, 'Gold'); ?>
                </div>
                <div class="bs-row">
                    <?php echo $this->renderSponsors('silver', 'bs-col-md-3', false, 'Silver'); ?>
                </div>
            </div>
            <div class="bs-col-md-12">
                <?php echo $this->renderCustomSponsors('bs-col-md-4'); ?>
            </div>
        </div>
        <?php
    }

	/**
	 * FunctionDescription
	 *
	 * @param $colValue
	 */
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

        $this->renderQueryResults($the_query, 'custom', $colValue, true, 'Custom');
    }

	/**
	 * FunctionDescription
	 *
	 * @param        $type
	 * @param        $colValue
	 * @param bool   $withLabel
     * @param string $header
	 */
    public function renderSponsors($type, $colValue, $withLabel = false, $header = '') {
        $args = array(
            'numberposts'	=> -1,
            'post_type'		=> 'sponsor',
            'meta_key'		=> 'sponsor_type',
            'meta_value'	=> $type
        );
        $the_query = new WP_Query( $args );

        $this->renderQueryResults($the_query, $type, $colValue, $withLabel, $header);
    }

	/**
	 * FunctionDescription
	 *
	 * @param WP_Query $the_query
	 * @param          $type
	 * @param          $colValue
	 * @param          $withLabel
	 * @param string   $header
	 */
    protected function renderQueryResults(WP_Query $the_query, $type , $colValue, $withLabel, $header = '')
    {
        if ($the_query->have_posts()) {
            ?>
            <header class="post-heading">
                <div class="post-title-wrapper">
                    <h2 class="post-title"><?=$header?></h2>
                </div>
            </header>
            <div class="bs-row <?=$type?>-logos"><?php
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $sponsorUrl = get_field('direct_url');
                if (empty($sponsorUrl)) {
                    $sponsorUrl = get_the_permalink();
                }
                ?>

                <a href="<?php echo $sponsorUrl; ?>">
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