<?php

namespace PoTemplate\Extension;

use PoTemplate\Engine;
use LogicException;

/**
 * This static class can be used to load javascript or css resources from
 * within your controllers
 *
 * @author Wim Mostmans
 */
class AssetCssJs implements ExtensionInterface
{

	/**
	 * Key to use in the registry to save a list of js assets
	 *
	 * @var string
	 */
	const JAVASCRIPT_RESOURCE_KEY = 'javascript_assets';

	/**
	 * Key to use in the registry to save a list of css assets
	 *
	 * @var string
	 */
	const CSS_RESOURCE_KEY = 'css_assets';	

	/**
	 * Javascript resource type
	 *
	 * @var string
	 */
	const TYPE_JS = 'js';

	/**
	 * CSS resource type
	 *
	 * @var string
	 */
	const TYPE_CSS = 'css';

	private static $registry = array();

	/**
     * Register extension function.
     * @return null
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('assetcssjs', [$this, 'getObject']);
    }

	/**
     * getObject function.
     * @return this
     */
	public function getObject()
    {
        return $this;
    }

	/**
	 * Load a javascript asset
	 *
	 * @param string $url
	 * @param int $order Order weight, the higher the number, the earlier the script will be loaded
	 * @return void
	 */
	public static function js($url, $order = 0)
	{
		self::add($url, self::TYPE_JS, $order);
	}

	/**
	 * Load a stylesheet asset
	 *
	 * @param string $url
	 * @param int $order Order weight, the higher the number, the earlier the stylesheet will be loaded
	 * @return void
	 */
	public static function css($url, $order = 0)
	{
		self::add($url, self::TYPE_CSS, $order);
	}

	/**
	 * Render the js and/or css include html
	 *
	 * @param bool $include_js
	 * @param bool $include_css
	 * @param  function $modifier Callback function to modify the asset url
	 * @return string
	 */
	public static function render($include_js = TRUE, $include_css = FALSE, $modifier = null) 
	{
		$html = '';
		if($include_js) {
			if($list = self::get(self::JAVASCRIPT_RESOURCE_KEY)) {
				foreach($list as $data) {

					$url = $data['src'];

					if( is_callable($modifier) )
						$url = call_user_func($modifier, $url);

					$html .= self::script_tag($url);
				}
			}
		}
		if($include_css) {
			if($list = self::get(self::CSS_RESOURCE_KEY)) {
				foreach($list as $data) {

					$url = $data['src'];

					if( is_callable($modifier) )
						$url = call_user_func($modifier, $url);
					
					$html .= self::style_tag($url);
				}
			}
		}
		return $html;
	}

	/**
	 * Clean registry so previous added script or styles will be removed
	 * 
	 * @return void
	 */
	public static function reset() 
	{
		self::set(self::JAVASCRIPT_RESOURCE_KEY, null);
		self::set(self::CSS_RESOURCE_KEY, null);
	}

	/**
	 * Add a resource to the list to load
	 *
	 * @param string $url
	 * @param string $type
	 * @return void
	 */
	protected static function add($url, $type = self::TYPE_JS, $order = 0) 
	{
		$key = $type == self::TYPE_JS ? self::JAVASCRIPT_RESOURCE_KEY : self::CSS_RESOURCE_KEY;
		if($list = self::get($key)) {
			$list[] = array( 'src' => $url, 'order' => $order );
		} else {
			$list = array( array( 'src' => $url, 'order' => $order ) );
		}
		self::set($key, $list);
	}

	/**
	 * Generate a script tag from an url
	 *
	 */
	protected static function script_tag($src = '', $language = 'javascript', $type = 'text/javascript')
    {
        $script = '<scr'.'ipt';
		$script .= ' src="'.$src.'" ';
        $script .= 'language="'.$language.'" type="'.$type.'"';
        $script .= '></scr'.'ipt>' . PHP_EOL;

        return $script;
    }

    /**
	 * Generate a script tag from an url
	 *
	 */      
	protected static function style_tag($src = '', $rel = 'stylesheet', $type = 'text/css')
    {
        $link = '<link';
 		$link .= ' href="'.$src.'" ';
        $link .= 'rel="'.$rel.'" type="'.$type.'"';
        $link .= '></link>' . PHP_EOL;     
 
        return $link;
    }

    protected static function get($key) {
    	return isset(self::$registry[$key]) ? self::sort(self::$registry[$key], 'order') : null;
    }

    protected static function set($key, $value) {
    	self::$registry[$key] = $value;
    	return true;
    }

    /**
     * Sort an array of array items based on a certain key
     * @param  array $data 
     * @param  string $key Key to sort on
     * @return array 
     */
    public static function sort( $data, $sortBy, $order = SORT_DESC ) {

    	foreach( $data as $key => $row ) {
    		$sorter[$key] = $row[$sortBy];
    	}

    	array_multisort($sorter, SORT_DESC, $data);

    	return $data;
    }

}