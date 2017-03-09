#Pico Tumblr Gallery

This plugin for Pico CMS creates galleries from tagged Tumblr image posts.

##Install

* Register a new Tumblr application [here](https://www.tumblr.com/oauth/apps) and get the *OAuth Consumer Key* of the newly created app.

* Copy `PicoTumblrGallery.php` to the `plugins` subdirectory.

* Add the following config data to `config/config.php`:

```php
/*
 * PicoTumblrGallery plugin configuration
 */
$config['PicoTumblrGallery'] = array(
 'enabled' => true,
 'api_key' => '', // set this to your app's OAuth Consumer Key
 'blog'=>'' // set to the address of the source tumblr, ex: xyz.tumblr.com
);
```

##Usage

Add a new header tag `Gallery: tag` to the page where you want to show the gallery. Change its value to the tag you want to filter for the images coming from Tumblr.

Example:

```
---
Title: Gallery
Description: Pico is a stupidly simple, blazing fast, flat file CMS.
Gallery: logo
---
```
This will return to twig all the different formats and the caption (see below) of the images you've posted on tumblr with the tag `logo`.

###Theme integration

You can now use the returned data in your custom templates and layouts. To iterate over all the returned images:
```twig
{% for image in gallery %}
    <img src="{{ image.url_500 }}"> {{image.caption}} <hr>
{% endfor %}
```

Properties of an image:

* `{{ image.url_250 }}` - url of the 250 pixel width version of the image
* `{{ image.url_500 }}` - url of the 500 pixel width version of the image
* `{{ image.url_large }}` - url of the 1280 pixel width version of the image
* `{{ image.caption }}` - caption of the Tumblr image post
* `{{ image.id }}` - id of the Tumblr image post
