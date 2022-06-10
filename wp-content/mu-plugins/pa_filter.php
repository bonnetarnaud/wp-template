<?php
/*
Plugin Name: product attribut filter
Version:     1.0
Author:      Arnaud Bonnet
Author URI:  https://arnaudbonnet.fr
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
add_action('plugins_loaded', 'load_pa_filter');

function load_pa_filter() {
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    add_action('woocommerce_before_shop_loop','new_pa_filter', 10);
}

function new_pa_filter(){
    
    $parameters = array();

    foreach ($_GET as $key=>$value) {
        $parameters[] = array(
            'slug' => $key,
            'value' => urldecode($value),
        );
    };

    $tax_query = array();
    $tax_query = array();
    $cat_query = (isset($_GET['product_cat'])) ?$_GET['product_cat'] :'';
    
    $args = array(
        'numberposts' => -1,
        'order' => 'ASC',
        //'tax_query'        => $tax_query,
        'category' => $cat_query
    );
    
    $current_products_list = wc_get_products( $args );
    //dump($current_products_list);
    
    $current_terms = array();
    foreach($current_products_list as $product) {
            //dump($product->get_name());
        foreach ( $product->get_attributes() as $attribute ) {
                
                $loop_terms = wc_get_product_terms($product->get_id(),$attribute['name']);
                //if (in_array("Irix", $os)) {
                $current_terms = array_merge($loop_terms, $current_terms);
        }  
    }
    //dump($current_terms);
    $current_pa_lists = array();
    $current_terms_list_id = array();
   foreach($current_terms as $term) {
        $current_pa_lists[] = substr($term->taxonomy, 3); //substring remove pa_
        $current_terms_list_id[] = $term->term_taxonomy_id;
   }
    
    $current_pa_lists = array_unique($current_pa_lists);
    $current_terms_list_id = array_unique($current_terms_list_id);

    ob_start();
    ?>
    <form method="GET" action="">
     <?php 
     foreach($current_pa_lists as $pa_name) {
        echo get_dropdown_term($pa_name,$current_terms_list_id);
     }
     //
     wc_product_dropdown_categories();
    
        ?> 
	<input type="hidden" name="paged" value="1" />
    <input type="submit" value="filtrer">
</form>
<?php 
$contenu = ob_get_contents();
  ob_end_clean();
  echo $contenu;
}

function get_dropdown_term($pa_name,$include_terms_list) {
    $terms = get_terms( array(
        'taxonomy' => 'pa_'.$pa_name,
        'include' => $include_terms_list,
    ) );
    
    $param_pa_slug = (isset($_GET['filter_'.$pa_name])) ? $_GET['filter_'.$pa_name] : '';
 
    ob_start();
    ?>
    <select name="filter_<?php echo $pa_name ?>">
    <option value="">...</option>
        <?php foreach($terms as $term) :?>
            <?php dump($term); ?>
            <option value="<?php echo $term->slug ?>" <?php echo selected( $term->slug, $param_pa_slug, false ) ?> > 
            <?php echo $term->name ?>
        </option>
        <?php endforeach; ?>
    </select>
    <?php
    $contenu = ob_get_contents();
    ob_end_clean();
    return $contenu;
}



?>