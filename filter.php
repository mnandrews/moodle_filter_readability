<?php
// GENERAL TODO's - for domains where we know we can't get useful data (due to password) display message.
// ft.com - display message
// ebscohost.com - don't attempt anything just display link as a link!

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Filter converting URLs in the text to HTML links
 *
 * @package    filter
 * @subpackage urltoreadibility
 * @copyright  2013 Mark Andrews <mnandrews@me.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class filter_readability extends moodle_text_filter {

    /**
     * @var array global configuration for this filter
     *
     * This might be eventually moved into parent class if we found it
     * useful for other filters, too.
     */
    protected static $globalconfig;

    /**
     * Apply the filter to the text
     *
     * @see filter_manager::apply_filter_chain()
     * @param string $text to be processed by the text
     * @param array $options filter options
     * @return string text after processing
     */
    public function filter($text, array $options = array()) {
    
        if (!isset($options['originalformat'])) {
            // if the format is not specified, we are probably called by {@see format_string()}
            // in that case, it would be dangerous to replace URL with the link because it could
            // be stripped. therefore, we do nothing
            return $text;
        }
        if (in_array($options['originalformat'], explode(',', $this->get_global_config('formats')))) {
        	$originalText = $text;
            $this->convert_urls_into_previews($text);
        }
        
       return $text; 
    }

    ////////////////////////////////////////////////////////////////////////////
    // internal implementation starts here
    ////////////////////////////////////////////////////////////////////////////

    /**
     * Returns the global filter setting
     *
     * If the $name is provided, returns single value. Otherwise returns all
     * global settings in object. Returns null if the named setting is not
     * found.
     *
     * @param mixed $name optional config variable name, defaults to null for all
     * @return string|object|null
     */
    protected function get_global_config($name=null) {
        $this->load_global_config();
        if (is_null($name)) {
            return self::$globalconfig;

        } elseif (array_key_exists($name, self::$globalconfig)) {
            return self::$globalconfig->{$name};

        } else {
            return null;
        }
    }

    /**
     * Makes sure that the global config is loaded in $this->globalconfig
     *
     * @return void
     */
    protected function load_global_config() {
        if (is_null(self::$globalconfig)) {
            self::$globalconfig = get_config('filter_urltolink');
        }
    }

    /**
     * Given some text this function converts any URLs it finds into HTML links
     *
     * @param string $text Passed in by reference. The string to be searched for urls.
     */
    protected function convert_urls_into_previews(&$text) {
    global $CFG;
        //I've added img tags to this list of tags to ignore.
        //See MDL-21168 for more info. A better way to ignore tags whether or not
        //they are escaped partially or completely would be desirable. For example:
        //<a href="blah">
        //&lt;a href="blah"&gt;
        //&lt;a href="blah">
        $filterignoretagsopen  = array('<a\s[^>]+?>');
        $filterignoretagsclose = array('</a>');
        filter_save_ignore_tags($text,$filterignoretagsopen,$filterignoretagsclose,$ignoretags);

        // Check if we support unicode modifiers in regular expressions. Cache it.
        // TODO: this check should be a environment requirement in Moodle 2.0, as far as unicode
        // chars are going to arrive to URLs officially really soon (2010?)
        // Original RFC regex from: http://www.bytemycode.com/snippets/snippet/796/
        // Various ideas from: http://alanstorm.com/url_regex_explained
        // Unicode check, negative assertion and other bits from Moodle.
        static $unicoderegexp;
        if (!isset($unicoderegexp)) {
            $unicoderegexp = @preg_match('/\pL/u', 'a'); // This will fail silently, returning false,
        }

        // TODO MDL-21296 - use of unicode modifiers may cause a timeout
        $urlstart = '(?:http(s)?://|(?<!://)(www\.))';
        
        $domainsegment = '(?:[\pLl0-9][\pLl0-9-]*[\pLl0-9]|[\pLl0-9])';
        $numericip = '(?:(?:[0-9]{1,3}\.){3}[0-9]{1,3})';
        $port = '(?::\d*)';
        $pathchar = '(?:[\pL0-9\.!$&\'\(\)*+,;=_~:@-]|%[a-f0-9]{2})';
        $path = "(?:/$pathchar*)*";
        $querystring = '(?:\?(?:[\pL0-9\.!$&\'\(\)*+,;=_~:@/?-]|%[a-fA-F0-9]{2})*)';
        $fragment = '(?:\#(?:[\pL0-9\.!$&\'\(\)*+,;=_~:@/?-]|%[a-fA-F0-9]{2})*)';

        $regex = "(?<!=[\"'])$urlstart((?:$domainsegment\.)+$domainsegment|$numericip)" .
                "($port?$path$querystring?$fragment?)(?<![]),.;])";
        
        if ($unicoderegexp) {
            $regex = '#' . $regex . '#ui';
        } else {
            $regex = '#' . preg_replace(array('\pLl', '\PL'), 'a-z', $regex) . '#i';
        }
		$readability_baseURL = 'http://www.readability.com/api/content/v1/parser?url=';
        $readability_token = $CFG->filter_readability_token;
        
        /* START ----- Code to extract URL's, place into arrays and transform into readability previews */
        preg_match_all ($regex, $text, $urlStore, PREG_SET_ORDER); //Gets urls and stores in array
        
        
        foreach ($urlStore as $u) { //loops through each URL and grabs previews
        	$linkURL = 'http'.$u[1].'://'.$u[2].$u[3].$u[4];

        	
  				
  				$exclude = '/(google.com|yahoo.com|bbc.co.uk|pdf|png)/';
  				preg_match($exclude, $linkURL,$counter);
  				
  				
  				$counter = count($counter);
  				

            	if ($counter >= 1) {
            	$text = $linkURL;
            	//echo $text;
            	}
            	else {
        			$urlcontents = cGetFile($readability_baseURL.$linkURL."&token=".$readability_token);
        			$jsonvalue = json_decode($urlcontents,true);
        			$jsonErrorvalue = json_last_error();

        
        			if ($jsonErrorvalue == 0) { //If error in Json don't parse 
        				$textReplace = Process_JSON ($jsonvalue);
        			}
        	
        			/* perform find and replace on original url to insert the preview */
        				
        				$text = str_replace ($linkURL, $textReplace, $text);
        				
        			}        
      
    			}
    
			}    
		}


function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

/* Function to manage the assembly of the final output
Input @json information
Output string containing html output

*/
function Process_JSON($json) {
global $CFG;

/* reading values from settings screen and acting */



if (isset($json['error'])) {
	return 'Errorflag';
} //If readibility can't process the JSON it returns an 'error' node. Using this to stop the processing
else {


	if ($CFG->filter_readability_toggle_intro == 1	) { //Display intro or not
		if (!$json['dek']) //selects which introduction to display 
			{$link_intro = $json['excerpt'];}
		else {$link_intro = $json['dek'];}
	} else
		{$link_intro = '';
	}

	if ($CFG->filter_readability_toggle_fullcontent == 1) {
		$full_content = '<div class="webpageContainer"><div class="webpageContent"'.$json['content'].'</div>';
	} else 
	{$full_content = '';}

	if ($CFG->filter_readability_toggle_image == 1) { //Display weblink images or not
		$link_image = '<img class="media-object" src="'.$json['lead_image_url'].'"/></a>';
		} else
		{$link_image = '';}

	if ($CFG->filter_readability_toggle_info == 1) { //Display domain and author information
		/* from web address */
		$domain = substr($json['domain'], strpos($json['domain'],'.')+1);
		/* get favicon */
	
		$domain_img = '<img class="nolink" src="http://g.etfv.co/http://'.$json['domain'].'" /> ';
	
	/* if author exists then display bullet before */
	if ($json['author']) 
		{$author = ' &#149; '.$json['author'];
		} else {$author = "";}

	$link_info = '<small class="nolink">'.$domain_img.$json['domain'].$author.'</small>';
	} else
	{$link_info = '';}

	
	/* Put all the links together */    
        		$textReplace = '<div class="readability_filter media">';
        		$textReplace .= '<a class="pull-left link_image" href="'.$json['url'].'" target="_new">';
        		$textReplace .= $link_image;
        		$textReplace .= '<div class="media-body">';
        		$textReplace .= '<a href="'.$json['url'].'" target="_new">'.$json['title'].'</a><br />';
        		$textReplace .= $link_info;
        		$textReplace .= '<div class="media">'.$link_intro.'</div>';
        		$textReplace .= $full_content;
        		$textReplace .= '</div></div>';
        	return $textReplace;
}
}


function cGetFile($urlString){
	$curl_handle = curl_init();
	curl_setopt($curl_handle, CURLOPT_URL,$urlString);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		return curl_exec($curl_handle);
	curl_close($curl_handle);
}



/**
 * Change links to images into embedded images.
 *
 * This plugin is intended for automatic conversion of image URLs when FORMAT_MOODLE used.
 *
 * @param  $link
 * @return string
 */
function filter_readibility_img_callback($link) {
    if ($link[1] !== $link[3]) {
        // this is not a link created by this filter, because the url does not match the text
        return $link[0];
    }
    return '<img class="filter_readibility_image" alt="" src="'.$link[1].'" />';
}

