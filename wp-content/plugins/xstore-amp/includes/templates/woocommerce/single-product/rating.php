<?php
/**
 * Description
 *
 * @package    rating.php
 * @since      1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

if ( $rating_count > 0 ) : ?>
	<div class="woocommerce-product-rating" role="button" tabindex="-1" on="tap:woocommerce-tabs-accordion.scrollTo(duration=200), woocommerce-tabs-accordion.expand(section='woocommerce-tab-reviews')">
		<?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
		<?php if ( comments_open() ) : ?>
			<?php //phpcs:disable ?>
			<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'woocommerce' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)</a>
			<?php // phpcs:enable ?>
		<?php endif ?>
	</div>

<?php endif; ?>