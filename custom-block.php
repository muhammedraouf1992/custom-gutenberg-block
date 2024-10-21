<?php
/*
  Plugin Name: custom gutenberg block
  Description: add custom blocks to gutenberg
  Version 1.0
  Author: raouf
  Author URI: https://www.linkedin.com/in/muhammedraouf92
*/

if(!defined("ABSPATH")){
    exit;
}
class customGutenbergBlock{
    function __construct()
    {
        add_action( 'enqueue_block_editor_assets',array($this,'cgb_add_admin_js') );
        add_action( 'init', array( $this,'cgb_random_products_block_register') );
        add_action( 'wp_enqueue_scripts', array( $this,'cgb_enqueue_styles') );
    }

    function cgb_add_admin_js(){
        if ( ! function_exists( 'WC' ) ) {
            // WooCommerce is not active, do not enqueue scripts
            return;
        }
        wp_enqueue_script(
            'cgb-admin-js',
            plugin_dir_url(__FILE__) . 'build/index.js',
            array('wp-blocks', 'wp-element', 'wp-editor'),
        );
    }
    function cgb_random_products_block_register() {
        register_block_type( 'custom-plugin/woocommerce-random-products', array(
            'render_callback' => array( $this,'cgb_random_products_block_render'),
        ) );
    }


    function cgb_random_products_block_render( $attributes ) {
        if ( ! function_exists( 'WC' ) ) {
            // to make sure if woocommerce is installed
            return; 
        }
        ob_start();

        $products= new WP_Query(array(
            'post_type'      => 'product',  
            'posts_per_page' => 3,         
            'orderby'        => 'date',    
            'order'          => 'DESC',    
        ));
        if ($products->have_posts()) {?>

        <div class='random-products-block'>
        <?php while($products->have_posts()){
                $products->the_post();
                $product = wc_get_product( get_the_ID() );
        ?>
        <a href="<?php the_permalink(  ) ?>">

        
        <div class="product">
            <img src="<?php echo esc_url( wp_get_attachment_url( $product->get_image_id() ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
            <h3><?php echo esc_html( get_the_title() ); ?></h3>
            <p><?php echo esc_html( $product->get_price() ); ?></p>
        </div>
        </a>
            <?php 
            
        }
            wp_reset_postdata();?>
        </div>
       <?php } else { ?>
            <p>No products found</p>
        <?php }
        $output=ob_get_clean();
        return $output;
    }

    function cgb_enqueue_styles(){
        wp_enqueue_style( 'cgb-custom-style', plugin_dir_url( __FILE__ ) . '/src/style.css', array(), '1.0.0', 'all' );

    }
    
}

$customGutenbergBlock=new customGutenbergBlock();



