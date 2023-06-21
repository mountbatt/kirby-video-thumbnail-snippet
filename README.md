# kirby-video-thumbnail-snippet
 If you have an URL of Vimeo or YouTube, this snippet returns an image-tag with a pre-downloaded video-thumbnail.
 The thumbnail image gets downloaded (if it not exists) into the current page directory and can handled like every other $file Object of Kirby CMS.

# Usage in your template

````
<?php snippet("video-thumbnail", ['video_url' => $page->video_url()]); ?>
````

# Customization

You can customize the output in the last rows of the snippet file

````
<?php if($final_thumb != ""): ?>
  <img class="img-fluid video-thumbnail" src="<?= $final_thumb->url(); ?>">
<?php endif; ?>
````

# Version
1.0
