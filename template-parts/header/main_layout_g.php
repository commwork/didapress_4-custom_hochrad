<?php
$layout_ID_header = get_query_var( 'layout_ID_header', '' );
$color_ID_header  = get_query_var( 'color_ID_header', '' );
$header_headline  = '';
// Read as array — do not peek with have_rows()/break (breaks ACF group loop state).
$header_has_foot  = didapress_4_header_footer_has_foot_content( get_sub_field( 'header-footer' ) );
$header_cards     = get_sub_field( 'header_cards_content' );
$header_cards     = is_array( $header_cards ) ? $header_cards : array();
$card_count       = 0;
foreach ( $header_cards as $header_card ) {
	$card_image = isset( $header_card['image'] ) ? (int) $header_card['image'] : 0;
	$card_title = trim( (string) ( $header_card['title'] ?? '' ) );
	$card_link  = $header_card['link_button'] ?? null;
	$has_link   = is_array( $card_link ) && ! empty( $card_link['url'] );
	if ( $card_image > 0 || $card_title !== '' || $has_link ) {
		++$card_count;
	}
}
$card_count = max( 0, min( 5, $card_count ) );
?>
<section class="section section--header <?php echo esc_attr( trim( didapress_4_bem_from_layout( $layout_ID_header ) . ' ' . didapress_4_bem_from_color( $color_ID_header ) ) ); ?>">

    <!-- Custom text + video background -->
    <?php if ( is_front_page() ) : ?>
        <div class="header-video-bg is-paused" data-header-video>
            <div class="header-preloader" aria-hidden="true">
                <?php
                $header_preloader_svg = get_stylesheet_directory() . '/assets/svg/bouncing-circles.svg';
                if ( is_readable( $header_preloader_svg ) ) {
                    // Theme SVG; keep SMIL <animate>, which kses would strip.
                    echo file_get_contents( $header_preloader_svg ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
            </div>
            <div class="header-video-bg__content">
                <div class="section__head">
                    <h1 class="h1">Willkommen am <br /> Gymnasium Hochrad.</h1>
                </div>
                
                <div class="section__foot">
                    <div class="intro-text lcnmb"><p>Gemeinsam an Herausforderungen lernen <br /> in internationalen Bezügen.</p></div>
                </div>
            </div>

            <img
                class="header-video-bg__placeholder"
                src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/video-placeholder.jpg' ); ?>"
                alt=""
                width="1000"
                height="751"
                decoding="async"
            >
            <video class="header-video-bg__media header-video__media" muted loop playsinline preload="metadata">
                <source src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/video/Hochrad_Drohnenvideo.mp4' ); ?>" type="video/mp4" media="(min-width: 768px)">
            </video>

            <button type="button" class="header-video__stop" aria-label="<?php esc_attr_e( 'Video stoppen', 'didapress_4' ); ?>">
                <span class="icon-wrapper size-24" aria-hidden="true"></span>
            </button>
            <button type="button" class="header-video__play" aria-label="<?php esc_attr_e( 'Video abspielen', 'didapress_4' ); ?>">
                <span class="icon-wrapper size-24" aria-hidden="true"></span>
            </button>
        </div>
    <?php endif; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 block-xy--custom">
                <!-- Header + Footer -->
                <?php /* if ( have_rows( 'header-footer' ) || $header_has_foot ) : ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-xl-8 head-foot-wrapper">
                                <div class="section__head">
                                    <?php while ( have_rows( 'header-footer' ) ) : the_row();
                                        $page_header_titles = didapress_4_get_page_header_module_titles();
                                        $header_headline    = $page_header_titles['headline'];
                                        ?>
                                        <?php if ( $page_header_titles['subheadline'] ) : ?>
                                            <span class="subheadline">
                                                <?php echo wp_kses_post( $page_header_titles['subheadline'] ); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ( $header_headline ) : ?>
                                            <h1 class="h1">
                                                <?php echo wp_kses_post( $header_headline ); ?>
                                            </h1>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </div>

                                <?php if ( $header_has_foot ) : ?>
                                    <!-- Module Footer -->          
                                    <div class="section__foot">
                                        <?php while ( have_rows( 'header-footer' ) ) : the_row(); ?>
                                            <?php if ( get_sub_field( 'intro_text' ) ) : ?>
                                                <div class="intro-text lcnmb">
                                                    <?php echo wp_kses_post( get_sub_field( 'intro_text' ) ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ( have_rows( 'buttons_primary' ) ) : ?>
                                                <div class="section__buttons--primary">
                                                    <?php while ( have_rows( 'buttons_primary' ) ) : the_row(); ?>
                                                        <?php $link = get_sub_field( 'link' ); ?>
                                                        <?php if ( $link ) : ?>
                                                            <a href="<?php echo esc_url( $link['url'] ); ?>" class="btn btn--primary" target="<?php echo esc_attr( $link['target'] ); ?>">
                                                                <?php echo esc_html( $link['title'] ); ?>
                                                                <span class="icon-wrapper size-1-25em">                                                    
                                                                </span>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                    </div>                         
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; */ ?>

                <?php if ( $card_count > 0 && have_rows( 'header_cards_content' ) ) : ?>                    
                    <div class="header-cards header-cards--count-<?php echo esc_attr( (string) $card_count ); ?>">
                        <?php while ( have_rows( 'header_cards_content' ) ) : the_row(); ?>
                            <?php
                            $image       = (int) get_sub_field( 'image' );
                            $title       = trim( (string) get_sub_field( 'title' ) );
                            $link_button = get_sub_field( 'link_button' );
                            $has_link    = is_array( $link_button ) && ! empty( $link_button['url'] );
                            if ( $image < 1 && $title === '' && ! $has_link ) {
                                continue;
                            }
                            $target   = $has_link && isset( $link_button['target'] ) && $link_button['target'] !== ''
                                ? (string) $link_button['target']
                                : '_self';
                            $card_tag = $has_link ? 'a' : 'article';
                            ?>
                            <<?php echo tag_escape( $card_tag ); ?>
                                class="header-card<?php echo $has_link ? ' pseudo-link' : ''; ?>"
                                <?php if ( $has_link ) : ?>
                                    href="<?php echo esc_url( $link_button['url'] ); ?>"
                                    target="<?php echo esc_attr( $target ); ?>"
                                    <?php echo '_blank' === $target ? ' rel="noopener noreferrer"' : ''; ?>
                                <?php endif; ?>
                            >
                                <?php if ( $image > 0 ) : ?>
                                    <div class="header-card__image">
                                        <?php
                                        echo wp_get_attachment_image(
                                            $image,
                                            'large',
                                            false,
                                            array(
                                                'class'   => 'img-fluid lazyload',
                                                'loading' => 'lazy',
                                                'alt'     => $title !== '' ? $title : ( $header_headline ?: '' ),
                                                'sizes'   => '(min-width: 992px) 20vw, 100vw',
                                            )
                                        );
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ( $title !== '' || $has_link ) : ?>
                                    <div class="header-card__body stack stack--s">
                                        <?php if ( $title !== '' ) : ?>
                                            <h2 class="header-card__title">
                                                <?php echo esc_html( $title ); ?>
                                            </h2>
                                        <?php endif; ?>
                                        <?php if ( $has_link ) : ?>
                                            <span class="btn btn--secondary header-card__link">
                                                <?php echo esc_html( (string) ( $link_button['title'] ?? '' ) ); ?>
                                                <span class="icon-wrapper size-1-25em"></span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </<?php echo tag_escape( $card_tag ); ?>>
                        <?php endwhile; ?>
                    </div>                    
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
