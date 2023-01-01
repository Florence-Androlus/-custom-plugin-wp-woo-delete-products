<?php 

 namespace wdp\Classes;

 class Readfile 
 {

    static public function init($fileName)
    {
        global $wpdb;
        $file = fopen($fileName, "r");
        $compteur=0;
        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

            $UGS=$column[0];
            $UGS=substr_replace($UGS,"20",2,0);
            $UGS = substr_replace($UGS,"05",6,0);

            $sql = 'SELECT product_id FROM `'.$wpdb->prefix.'wc_product_meta_lookup` WHERE sku LIKE "%'.$UGS.'"';

            $result =$wpdb->get_results($sql);

            if( !empty($result) ){

                foreach( $result as $enreg ) {
                    $product_id=$enreg->product_id ;
                }
                $product_id=intval($product_id);


                
                $sql_product_id = 'SELECT post_name FROM `'.$wpdb->prefix.'posts` where ID="'.$product_id.'"';
                $product =$wpdb->get_results($sql_product_id);
                foreach( $product as $enreg ) {
                    $product_slug="/produit/".$enreg->post_name ;
                }


                $sql_product_category_id = 'SELECT term_taxonomy_id FROM `'.$wpdb->prefix.'term_relationships`where object_id="'.$product_id.'"';
                $product_category_id =$wpdb->get_results($sql_product_category_id);

                foreach( $product_category_id as $enreg ) {
                    $category_id=$enreg->term_taxonomy_id ;
                    $sql_taxonomy = 'SELECT taxonomy,parent FROM `'.$wpdb->prefix.'term_taxonomy`where term_id="'.$category_id.'"';
                    $taxonomy =$wpdb->get_results($sql_taxonomy);
                    foreach( $taxonomy as $enreg ) {

                    if( $enreg->taxonomy === "product_cat" and $enreg->parent==="0")
                    {
                        $sql_category =' SELECT slug FROM `'.$wpdb->prefix.'terms`where term_id="'.$category_id.'"';
                        $category =$wpdb->get_results($sql_category);

                        foreach( $category as $enreg ) {
                            $category=$enreg->slug ;
                        }
                        

                        $url = site_url()."/categorie-produit/".$category."/";

                        $table =$wpdb->prefix."redirection_items";

                        $sql_redirection ="INSERT INTO `$table` (`url`, `match_url`, `match_data`, `regex`, `position`, `last_count`, `last_access`, `group_id`, `status`, `action_type`, `action_code`, `action_data`, `match_type`, `title`) VALUES ('$product_slug', '$product_slug', NULL, 0, 0, 1, now(), 1, 'enabled', 'url', 301, '$url', 'url', NULL)";
                        $result =$wpdb->get_results($sql_redirection);

                        //wp_trash_post( $product_id );

                        wp_update_post(array(
                            'ID'            =>  $product_id,
                            'post_status'   =>  "draft"
                        ));
                        
                        $compteur++;
                        if ( $wpdb->last_error ) {
                            echo 'wpdb error: ' . $wpdb->last_error;
                        }
                    }
                }

                }


            }

        }

        fclose($file);
        return $compteur;

    }
}
