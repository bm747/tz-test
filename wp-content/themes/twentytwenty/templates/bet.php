<?
//Template Name: Ставки
?>

<?php get_header() ?>
<div class="section-inner">
<div id="content">
	<div id="center">
		
	<div class="wrap" id="center-panel">
		<h2>Сделать ставку</h2>

		 <form name="postbet" id="postbet" action="<?php echo admin_url('admin-ajax.php?action=addbet') ?>" method="POST">
		  <table class="input-table" style="width:100%; margin:0 auto;">

		   <tr>
		   <td colspan="2">
		    <dl>
			 <dt><label for="bet_name">Название ставки:</label></dt>
			 <dd><input type="text" name="bet_name" id="bet_name" value=""/></dd>
			</dl>
			
			<dl>
			 <dt><label for="bet_text">Текст ставки:</label></dt>
			 <dd><textarea name="bet_text" id="bet_text" rows="3" cols="3"></textarea></dd>
			</dl>
			 <dl>
			  <dt><label for="bet_type">Тип ставки:</label></dt>
			  <dd><select name="bet_type" id="bet_type">
					  <option>Ординар</option>
					  <option>Экспресс</option>
					</select>
			  </dd>
			 </dl>
			</td>

		   </tr>

		  </table>
		  <div style="text-align:center">
			<input type="submit" class="button" name="send" value="Отправить" />
		  </div>
		 </form>
		</div> <!-- /#center-panel -->					

	</div>
</div>

</div>
<div style="clear: both; height: 20px;"></div>

<div class="section-inner">
<div id="content">
	<div id="center">

<?
$args = array( 'post_type' => 'bet', 'posts_per_page' => 10 );
$the_query = new WP_Query( $args );

if ( $the_query->have_posts() ){
	while ( $the_query->have_posts() ){
		$the_query->the_post();
		$title = get_the_title();
		$content = get_the_content();

echo '<a href="' . get_permalink() . '"><h3>'.$title.'</h3></a> <p>'.$content.'</p><hr class="post-separator styled-separator is-style-wide section-inner">';
	}
} else {
	echo wpautop( 'Ставок не найдено.' );
}

?>

</div></div></div>

<?

get_footer(); 

?>