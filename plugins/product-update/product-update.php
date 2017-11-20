<?php
/**
 *
 * @link              http://wetstonetech.com
 * @since             1.0.0
 * @package           Product_Update
 *
 * @wordpress-plugin
 * Plugin Name:       Product Update
 * Plugin URI:        http://wetstonetech.com
 * Description:       Updates download links for DAT files and other downloads based on folder contents
 * Version:           1.0.0
 * Author:            Wil Conklin
 * Author URI:        http://wetstonetech.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
     die;
}
 
// Include the dependencies needed to instantiate the plugin.
foreach ( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
    include_once $file;
}
 
/**
 * Starts the plugin.
 *
 * @since 1.0.0
 */

add_shortcode("GDS", "display_GDS");
 
function display_GDS($atr){
	
        $dataset = '../protected/Gargoyle Investigator/Dataset Updates';
		$pdfEng = '../protected/Gargoyle Investigator/Release Notes';
		$pdfSpa = '../protected/Gargoyle Investigator/Release Notes - Spanish';
		$hashes = '../protected/Gargoyle Investigator/Supplemental Gargoyle Hashes';
		
		$datasetFiles = scan_dir($dataset);
		$pdfEngFiles = scan_dir($pdfEng);
		$pdfSpaFiles = scan_dir($pdfSpa);
		$hashFiles = scan_dir($hashes);		
		
		$aca_table = "";
		$aca_table .= '<table style="border: 1px solid black">';
		$aca_table .= '<tr style="border: 1px solid black">';
		$aca_table .= '<th colspan="2" style="padding-left:7px;">Supplemental Gargoyle Hashes</th>';
		$aca_table .= '</tr>';
		$aca_table .= '<tr style="border: 1px solid black">';
		$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="'.$hashes.'/'.$hashFiles[0].'" target="_blank" style="text-decoration:none; color:green;">Hash File</a></td><td><img src="http://localhost/wordpress/wp-content/uploads/2017/11/zip-icon.png" /></td>';
		$aca_table .= '</tr>';
		$aca_table .= '</table>';
		
		echo $aca_table;
		
		for ($aca_i = 0; $aca_i < 3; $aca_i++) {
			$aca_header = explode("_",$datasetFiles[$aca_i]);
			
			$aca_table = "";
			$aca_table .= '<table style="border: 1px solid black">';
			$aca_table .= '<tr style="border: 1px solid black">';
			$aca_table .= '<th colspan="2" style="padding-left:7px;">'. $aca_header[1] ." ". $aca_header[0] .'</th>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="'.$dataset.'/'.$datasetFiles[$aca_i].'" target="_blank" style="text-decoration:none; color:green;">Dataset File</a></td><td><img src="http://localhost/wordpress/wp-content/uploads/2017/11/cab-icon.png" /></td>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="'.$pdfEng.'/'.$pdfEngFiles[$aca_i].'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - English</a></td><td><img src="http://localhost/wordpress/wp-content/uploads/2017/11/pdf-icon.png" /></td>';
			$aca_table .= '</tr>';
			$aca_table .= '<tr style="border: 1px solid black">';
			$aca_table .= '<td style="padding-left:7px; width:475px; height:20px;"><a href="'.$pdfSpa.'/'.$pdfSpaFiles[$aca_i].'" target="_blank" style="text-decoration:none; color:green;">Dataset Release Notes - Spanish</a></td><td><img src="http://localhost/wordpress/wp-content/uploads/2017/11/pdf-icon.png" /></td>';
			$aca_table .= '</tr>';
			$aca_table .= '</table>';
			
			echo $aca_table;
		}

}

function scan_dir($dir) {
    $ignored = array('.', '..');

    $files = array();    
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}