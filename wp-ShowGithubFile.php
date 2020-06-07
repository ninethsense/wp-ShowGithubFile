<?php
/**
 * Plugin Name: wp-ShowGithubFile
 * Description:       Show a file, preferably source code file content in a WordPress blog post or page
 * Version:           1.0.0
 * Author:            NinethSense
 * Author URI:        https://blog.ninethsense.com/
 * License:           GPL v2 or later
 */


    add_shortcode( "GitHub", "ShowGitHub" );
    function ShowGitHub($atts) {
        if (!isset($atts["file"]) ||  strpos(strtolower($atts["file"]), "github" ) == 0 ) {
            // Also, I want only files from GitHub. Modify this to show file from any URL
            return "[Invalid file]";
        }
        
        $fh = @get_headers($atts["file"]);
        
        if (strpos($fh[0],"200") == 0) {
            return "[Invalid file]";
        }
        
        $fcontents = file_get_contents($atts["file"]);
        $notphp = false;

        
        if (strpos($fcontents, "?php") == false) {
            $fcontents = "<?php ".$fcontents;
            $notphp = true;
        }
        
        // Make use of PHP syntax highlighing. Something is better than nothing.
        $fcontents = highlight_string($fcontents, TRUE);
        if ($notphp) {
            $fcontents =  str_replace("&lt;?php", "",$fcontents);
            $notphp = false;
        }
        
        
        $fcontents = explode("<br />",$fcontents);
        $style = (isset($atts["style"])) ?$atts["style"]:"";
        $ret = "<div style='background-color:#dcd7ca;width:100%;overflow:auto;white-space:nowrap;border:solid 1px #aaa;font-size:10pt;$style;'>";
        for ($i=0;$i<sizeof($fcontents);$i++) {
            //$line = str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",$fcontents[$i]);
            $line = $fcontents[$i];
            $ret .= "<span style='font-size:10pt;display:block;width:40px;background-color:#aaa;float:left'>" . str_pad($i+1, 3,'0',STR_PAD_LEFT) ." </span><span style='width:100%;inline-block'>&nbsp;" . $line . "</span><br />";
            //$ret .= "<span style='display:block;width:40px;background-color:#aaa;float:left'>" . str_pad($i+1, 3,'0',STR_PAD_LEFT) ." </span><span style='display:inline-block;width:100%;background-color:#ddd;'>" . $line . "&nbsp;</span><br />";
        }
        $ret .= "</div>";
       
        
        return $ret;
        
    }
?>