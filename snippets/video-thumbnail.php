<?php 
 // we get: $video_url;
 $video = $video_url;
 
 $video = str_replace("www.youtube.com", "www.youtube-nocookie.com", $video);
 $hoster = ""; 
 $video_thumb = "";
 $hd_thumb = "";
 $sd_thumb = "";

 
 if(str_contains($video, "youtu")){
   $hoster = "YouTube";
   
   // youtu.be umwandeln:
   if(str_contains($video, "youtu.be")){
     $id = substr($video, strrpos($video, '/') + 1);
     $url =  "https://www.youtube.com/watch?v=$id";
   } else {
     $url = $video;
   }
   $query = parse_url($url, PHP_URL_QUERY);
   parse_str($query, $params);
   $videoID = isset($params['v']) ? $params['v'] : null;
   $hd_thumb = "https://img.youtube.com/vi/".$videoID."/maxresdefault.jpg";
   $sd_thumb = "https://img.youtube.com/vi/".$videoID."/hqdefault.jpg";
 }
 
 if(str_contains($video, "vimeo")){
   $hoster = "Vimeo";
   $url = $video;
   $regex = '/^https?:\/\/(www\.)?vimeo\.com\/([0-9]+).*$/';
   preg_match($regex, $url, $matches);
   $videoID = isset($matches[2]) ? $matches[2] : null;
   $endpoint = "https://vimeo.com/api/v2/video/$videoID.json";
   
   $target_filename = $videoID;    
   $current_dir = $page->contentFileDirectory();
   $file_exists = glob($current_dir."/".$target_filename.".*");
   if(empty($file_exists)){
     $json = file_get_contents($endpoint);
     $data = json_decode($json, true);
     $hd_thumb = isset($data[0]['thumbnail_large']) ? $data[0]['thumbnail_large'] : null;
   } else {
     $hd_thumb = $file_exists[0];
   }
 }
 
 $video_thumb = $hd_thumb;
 if($video_thumb == ""){
   $video_thumb = $sd_thumb;
 }

 if($video_thumb != ""){
   // save thumbnail file to current directory to serve it locally
   $ext = pathinfo($video_thumb, PATHINFO_EXTENSION);
   if(empty($ext)){
     $ext = "jpg";
   }
   $target_filename = $videoID.".".$ext;    
   $current_dir = $page->contentFileDirectory();
   if(!file_exists($current_dir."/".$target_filename)){
     $save_image = file_get_contents($video_thumb);
     file_put_contents($current_dir."/".$target_filename, $save_image);
   }
 }
 $final_thumb = $page->image($target_filename)->crop(960, 540);

?>
<?php if($final_thumb != ""): ?>
  <img class="img-fluid video-thumbnail" src="<?= $final_thumb->url(); ?>">
<?php endif; ?>