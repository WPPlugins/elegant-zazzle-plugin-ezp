<?php
/*
   Plugin Name: Elegant Zazzle Plugin (EZP)
   Plugin URI: http://www.zazzletools.com/elegant-zazzle-plugin/
   Description: A plugin to display products from Zazzle
   Author: Hupshee
   Version: 1.1 
   Author URI: http://www.zazzletools.com

   Installing
   1. Upload files to wp-content/plugins/zazzle-plugin
   2. Activate it through the plugin management screen.

   Changelog
   0.1 = First public release.
   0.2 = Updated functions with default parameters.
   1.0 = Major update. Now includes support for all Zazzle domains and currencies. Improved display.
   1.1 = Major update. Now includes support for all Zazzle domains and currencies. Improved display.
 */

/* License

   Elegant Zazzle Plugin (EZP)
   Copyright (C) 2010 zazzletools.com (hupshee@zazzletools.com)

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

// define some contants
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

// load library
if(!class_exists('EZP_Functions'))
{ 
    require_once(dirname(__FILE__) . '/functions.php');
}
require_once(dirname(__FILE__) . '/zazzle_widget.php');

// the plugin class
class EZP_ZazzlePlugin
{
    function shortcode_handler($atts) 
    {
        extract(shortcode_atts(array(
                        'store_name'                => '',
                        'product_line'              => '',
                        'product_type'              => '',
                        'search_term'               => '',
                        'grid_width'                => '900',
                        'grid_cell_size'            => 'large',
                        'grid_cell_spacing'         => '9',
                        'grid_cell_bg_color'        => 'FFFFFF',
                        'num_items'                 => '20',
                        'randomize'                 => 'false',
                        'start_page'                => '1',
                        'default_sort'              => 'popularity',
                        'show_pagination'           => 'true',
                        'show_sorting'              => 'true',
                        'show_product_description'  => 'true',
                        'show_product_creator'      => 'true',
                        'show_product_title'        => 'true',
                        'show_product_price'        => 'true',
                        'show_powered_by_zazzle'    => 'true',
                        'search_by_tags'            => 'false',
                        'domain'                    => 'com'
                        ), $atts));

        // search by tags
        if($search_by_tags == 'true')
        {
            $search_term = EZP_Functions::get_search_term_from_post_tags();            
        }

        // set all store display options
        $_GET['contributorHandle']      = $store_name;
        $_GET['productLineId']          = $product_line;
        $_GET['productType']            = $product_type;
        $_GET['keywords']               = $search_term;
        $_GET['gridWidth']              = $grid_width;
        $_GET['gridCellSize']           = $grid_cell_size;
        $_GET['gridCellSpacing']        = $grid_cell_spacing;
        $_GET['gridCellBgColor']        = $grid_cell_bg_color;
        $_GET['showHowMany']            = $num_items;
        $_GET['randomize']              = $randomize;
        $_GET['startPage']              = $start_page;
        $_GET['showPagination']         = $show_pagination;
        $_GET['showSorting']            = $show_sorting;
        $_GET['defaultSort']            = $default_sort;
        $_GET['showProductDescription'] = $show_product_description;
        $_GET['showByLine']             = $show_product_creator;
        $_GET['showProductTitle']       = $show_product_title;
        $_GET['showProductPrice']       = $show_product_price;
        $_GET['showPoweredByZazzle']    = $show_powered_by;
        $_GET['domain']                 = $domain;
        
        // set the baseurl
        $url = get_permalink();
        $url .= (strpos($url, '?') === false)? '?' : '&';
        $_GET['baseUrl']                = $url;

        // get the data
        ob_start ();
        include (EZP_Functions::get_path() . 'zstore.php');
        $output = ob_get_contents ();
        ob_end_clean ();
        return $output;
    }
}

add_action('wp_print_styles', array('EZP_Functions', 'add_styles'));
add_shortcode('ezp',     array('EZP_ZazzlePlugin', 'shortcode_handler'));

?>
