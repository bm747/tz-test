<?php

/*
* Plugin Name: tz-bets
*/


add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

function register_plugin_styles() {
	wp_register_style( 'tz-bets', plugins_url( 'tz_bets/_inc/tz_bets.css' ) );
	wp_enqueue_style( 'tz-bets' );
}


add_action( 'wp_enqueue_scripts', 'my_scripts_method' );
function my_scripts_method() {
    $script_url = plugins_url( '_inc/main.js', __FILE__ );
    wp_enqueue_script('custom-script', $script_url, array('jquery') );
}


register_activation_hook( __FILE__, 'bets_activate' );
function bets_activate() {

    add_role( 'custom_role1', 'Каппер',
        array(
            'read' => true,
            'level_0' => true
        )
    );

    add_role( 'custom_role2', 'Модератор',
        array(
            'read' => true,
            'level_0' => true
        )
    );
}


register_deactivation_hook( __FILE__, 'bets_deactivate' );

function bets_deactivate(){
    remove_role( 'custom_role1' );
    remove_role( 'custom_role2' );
}


register_uninstall_hook( __FILE__, 'bets_uninstall' );
 
function bets_uninstall(){
    delete_option( 'tz-bets_settings' );
}


function create_bets_taxonomy() {

  register_taxonomy('bettype', 'bet',array(
		'hierarchical'  => true,
		'labels'        => array(
			'parent_item'                 => null,
			'parent_item_colon'           => null,
			'show_admin_column'           => false,	    
			'menu_name'                   => __( 'Тип ставки' ),
		), 
		'show_ui'       => true
	));

}

add_action( 'init', 'create_bets_taxonomy' );


function create_bets_posttype() {

    $labels = array(
        'name' => _x( 'Ставки', 'Тип записей Ставки' ),
        'singular_name' => _x( 'Ставка', 'Тип записей Ставки' ),
        'menu_name' => __( 'Ставки' ),
        'all_items' => __( 'Все ставки' ),
        'view_item' => __( 'Смотреть ставку' ),
        'add_new' => __( 'Добавить новый' ),
        'edit_item' => __( 'Редактировать ставку' ),
        'update_item' => __( 'Обновить ставку' ),
        'search_items' => __( 'Искать ставку' ),
        'not_found' => __( 'Не найдено' ),
        'not_found_in_trash' => __( 'Не найдено в корзине' ),
    );

    $args = array(
        'label' => __( 'bet' ),
        'labels' => $labels,
        'supports' => array( 'title', 'editor',  'author',  'revisions'),
        'taxonomies' => array( 'bettype' ),
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'has_archive' => true,
        'publicly_queryable' => true,
        'rewrite' => array('slug' => 'bet'),
    );


    register_post_type( 'bet', $args );
}

add_action( 'init', 'create_bets_posttype', 0); 


add_action( 'add_meta_boxes', 'bet_add_metabox' );
 
function bet_add_metabox() {
 
	add_meta_box(
		'bet_vote', // ID нашего метабокса
		'Текущая ставка', // заголовок
		'bet_metabox_callback', // функция, которая будет выводить поля в мета боксе
		'bet',
		'normal',
		'default'
	);
 
}
 
function bet_metabox_callback( $post ) {
 
	$bet_vote = get_post_meta( $post->ID, 'bet_vote', true );

	echo '<table class="form-table">
		<tbody>
			<tr>
				<th><label for="seo_title">Значение</label></th>
				<td><input type="text" id="bet_vote" name="bet_vote" value="' . esc_attr( $bet_vote ) . '" class="regular-text"></td>
			</tr>
		</tbody>
	</table>';
 
}


add_action( 'wp_enqueue_scripts', 'myajax_data', 99 );
function myajax_data(){

    wp_localize_script( 'twentytwenty-script', 'myajax',
        array(
            'url' => admin_url('admin-ajax.php')
        )
    );

}


add_action( 'wp_ajax_addbet', 'addbet' ); 

//add_action( 'wp_ajax_nopriv_addbet', 'addbet' );

function addbet(){
    

    $betText = sanitize_text_field($_POST['betText']);
    $betDesc = sanitize_text_field($_POST['betDesc']);
    $betType = sanitize_text_field($_POST['betType']);

    // Создаем массив данных новой записи
    $post_data = array(
        'post_title'    => $betText,
        'post_content'  => $betDesc,
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type' => 'bet'
    );


    $post_id = wp_insert_post( $post_data );

    //update_post_meta( 16, 'bet_vote', $betText );

    wp_die();
}


add_action( 'wp_ajax_updatebet', 'updatebet' ); 

function updatebet(){
    

    $betVote = sanitize_text_field($_POST['betVote']);
    $betId = sanitize_text_field($_POST['betId']);

    update_post_meta( $betId, 'bet_vote', $betVote );

    wp_die();
}




add_filter( 'template_include', 'include_template_function', 1 );


function include_template_function( $template_path ) {
    if ( get_post_type() == 'bet' ) {
        if ( is_single() ) {
            if ( $theme_file = locate_template( array ( 'single-bet.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-bet.php';
            }
        }
    }
    return $template_path;
}


