<?php
/**
 * Tumblr plugin for Pico
 *
 * @author Lorincz Raul
 * @link http://sigmagfx.com
 * @license http://opensource.org/licenses/MIT
 */
class Pico_Tumblr {

	private $plugin_path;
	private $api_key = '';
	private $blog = '';
	private $tag;

	public function __construct()
	{
		$this->plugin_path = dirname(__FILE__);
	}

	public function before_read_file_meta(&$headers)
	{
		$headers['gallery'] = 'Gallery';
	}

	public function file_meta(&$meta)
	{
		$this->tag = $meta['gallery'];
	}

	private function get_tumblr_images($api, $blog, $tag)
	{
		$array_items = array();
		$url = 'http://api.tumblr.com/v2/blog/'.$blog.'/posts?type=photo&tag='.$tag.'&api_key='.$api;
		$array_items = json_decode(file_get_contents($url), true);
		return $array_items['response']['posts'];
	}

	public function before_render(&$twig_vars, &$twig)
	{
		// assign the images to the twig_vars
		$twig_vars['gallery'] = $this->get_tumblr_images($this->api_key, $this->blog, $this->tag);
		foreach ($twig_vars['gallery'] as &$image) {
			$temp_array = array();
			// lazy link to the image
			$temp_array['url_250'] = $image['photos'][0]['alt_sizes'][3]['url'];
			$temp_array['url_500'] = $image['photos'][0]['alt_sizes'][1]['url'];
			$temp_array['url_large'] = $image['photos'][0]['original_size']['url'];
			$temp_array['caption'] = $image['caption'];
			$temp_array['id'] = $image['id'];
			$image = $temp_array;
		}
		return;
	}
}
?>
