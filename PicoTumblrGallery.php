<?php
/**
 * Tumblr gallery plugin for Pico
 *
 * @author Lorincz Raul
 * @link http://sigmagfx.com
 * @license http://opensource.org/licenses/MIT
 * @version 1.x
 */
final class PicoTumblrGallery extends AbstractPicoPlugin
{
	private $api_key;
	private $blog;
	private $tag;
    
    protected $enabled = true;
    
    public function onConfigLoaded(&$config)
    {
        //get config vars
        if (isset($config['PicoTumblrGallery']['api_key'])) {
            $this->api_key = $config['PicoTumblrGallery']['api_key'];
        }
        if (isset($config['PicoTumblrGallery']['blog'])) {
            $this->blog = $config['PicoTumblrGallery']['blog'];
        }
    }

    public function onMetaHeaders(array &$headers)
    {
        $headers['gallery'] = 'Gallery';
    }

    public function onMetaParsed(array &$meta)
    {
        $this->tag = $meta['gallery'];
    }
    
    public function onPageRendering(Twig_Environment &$twig, array &$twigVariables, &$templateName)
    {
		$twigVariables['gallery'] = $this->get_tumblr_images($this->api_key, $this->blog, $this->tag);
		foreach ($twigVariables['gallery'] as &$image) {
			$temp_array = array();
			$temp_array['url_250'] = $image['photos'][0]['alt_sizes'][3]['url'];
			$temp_array['url_500'] = $image['photos'][0]['alt_sizes'][1]['url'];
			$temp_array['url_large'] = $image['photos'][0]['original_size']['url'];
			$temp_array['caption'] = $image['caption'];
			$temp_array['id'] = $image['id'];
			$image = $temp_array;
		}
    }
    
    private function get_tumblr_images($api, $blog, $tag)
	{
		$array_items = array();
		$url = 'http://api.tumblr.com/v2/blog/'.$blog.'/posts?type=photo&tag='.$tag.'&api_key='.$api;
		$array_items = json_decode(file_get_contents($url), true);
		return $array_items['response']['posts'];
	}
}