<?php
/** 
 * Top Trump Card View
**/
?>
<div class="top-trump-game">
	<div class="filters col-12">
		<div class="filter-values button-group sort-by-button-group ">
		</div>
	</div>
	<div class="top-trump-container grid">
		<?php while( $query->have_posts() ):?>
			<?php $query->the_post();?>
				<?php $trump_card_values = meta_finder( $category, get_the_ID() );?>
				<div class="col-6 col-12-sm top-trump" <?php ( !empty ( $trump_card_values['top_trump_image'] ) ) ?
						printf( 'style="background-image:url(\'%s\')"', $trump_card_values['top_trump_image'] ) : '';?>">
					<ul class="trump-values">
						<h3><?php the_title();?></h3>
						<?php foreach ( $trump_card_values as $key => $trump_card_value ):?>
							<?php if( !is_object( $trump_card_value ) ) continue;?>
							<?php if( is_trump_card_meta( $trump_card_value->name, $trump_card_value->type, get_the_title(), $key ) ) :?>
								<li class="trump-data"><span class="data-title"><?php echo $trump_card_value->name;?></span>: <span class="data-value <?php echo $trump_card_value->type;?> <?php echo $key;?>" sort-data-id="<?php echo $key;?>"><?php echo $trump_card_value->value;?></span></li>
							<?php endif;?>
						<?php endforeach;?>
					</ul>
				</div>
		<?php endwhile;?>	
	</div>
</div>