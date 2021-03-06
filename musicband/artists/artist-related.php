<?php
	global $post, $relatedpostid;
		//print_r(wp_get_object_terms($post->ID, 'artist_cat'));

	
		$terms = get_the_terms( $relatedpostid, 'artist_cat' ); // get an array of all the terms as objects.
		
		$termcat = array();
		
		foreach( $terms as $term ) {
		    $termcat[] = $term->slug; // save the slugs in an array
		}
		//print_r($termcat);
		//Columns for Album Thumbs
		$column_index = 0; $columns = 5;
		if( $columns == '5' ) { $class = 'col_fifth'; }
		if( $columns == '4' ) { $class = 'col_fourth'; }
		if( $columns == '3' ) { $class = 'col_third'; }
		
		//Full Width Album Image Sizes
		if( $columns == '5' ) { $width='470'; $height = '470' ; }
		if( $columns == '4' ) { $width='470'; $height = '470' ; }
		if( $columns == '3' ) { $width='470'; $height = '470' ; }

		$the_query = new WP_Query(
		array(
			'post_type' => 'artists',
			'tax_query' => array(
					array(
						'taxonomy' => 'artist_cat',
						'field' => 'slug',
						'terms' => $termcat
					)
				),
			'post_status' => 'publish',
			'posts_per_page' => 5,
			'post__not_in'	=>array($relatedpostid),
			'order' => 'ASC'
		)
		);
	$pcount = $the_query->post_count;
	if($pcount>=1){
	?>

		<div class="more-labels">
		<div class="inner">
		<h3 class="fancy-title"><?php _e('More Artist From:','musicplay'); ?>&nbsp;<?php global $relatedpostid;  echo get_the_term_list( $relatedpostid , 'artist_cat', '', ', ', '' ) ?></h3>
		<?php
		//print_r($the_query);
		while ( $the_query->have_posts() ) {
		$the_query->the_post();
		$art_postID = $the_query->post->ID;
		$column_index++;
		$last = ( $column_index == $columns && $columns != 1 ) ? 'end ' : '';
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $art_postID ), 'full', true ); ?>
	<div class="album-list  <?php echo $class. ' ' .$last; ?>" >
	<div class="albumpost_entry ">
	<?php if( has_post_thumbnail()){ ?>
	<div class="custompost_thumb port_img">
	<?php echo '<figure>';
		echo atp_resize( $art_postID, '', $width, $height, '', '' );
		echo '</figure>'; 
		echo '<div class="hover_type">';
		echo '<a class="hoverartist"  href="' .get_permalink(). '" title="' . get_the_title() . '"></a>';
		echo '</div>'; ?>
	</div>
	<?php } ?>
	<div class="album-desc">
	<h2 class="entry-title">
	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __( "Permanent Link to %s", 'musicplay' ), esc_attr( get_the_title() ) ); ?>"><?php the_title(); ?></a>
	</h2>
	<span class="label-text"><?php echo strip_tags(get_the_term_list( $art_postID , 'artist_cat','',', ','' )) ?></span>
	</div><!-- .custompost_details-->
	</div><!-- .custompost_entry -->
	</div><!-- .customposts -->
	<?php
		if( $column_index == $columns ){
		$column_index = 0;
		echo '<div class="clear"></div>';
		}}
	wp_reset_query();
	?>

	<div class="clear"></div>

	<!-- #post-<?php the_ID();?> -->
	</div>
	</div><!-- .label -->
	<?php } ?>