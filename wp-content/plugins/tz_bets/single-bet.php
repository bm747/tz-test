<?php get_header() ?>
<div class="section-inner">
<div id="content">
	<div id="center">
		

	
		<?php
			if ( have_posts() ) : while ( have_posts() ) : the_post(); 
		?>
		<div>
			<h2><?php the_title() ?></h2>
			<?php the_content() ?>

			<? 

				echo "Тип ставки: ";
				$terms = get_the_terms( $post->ID, 'bettype' );

				if( is_array( $terms ) ){
					foreach( $terms as $terms ){
						echo $terms->name.' ';
					}
				}
				
			?>

		</div>


		<?php 
			endwhile;
			else : echo "Ничего нет";  endif; 
		?> 
		

	</div>
</div><br>


<form name="updbet" id="updbet" action="<?php echo admin_url('admin-ajax.php?action=updatebet') ?>" method="POST">
<input name="bet_vote" id="bet_vote"></input>
<input name="bet_id" id="bet_id" type="hidden" value="<? the_id() ?>"></input>
<br><br>
<input type="submit" class="button" name="send" value="Сделать ставку" />
</form>


</div>
<div style="clear: both; height: 20px;"></div>
<?
get_footer(); 

?>