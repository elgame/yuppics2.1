<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Photos facebook</title>
	
</head>
<body>

	<?php if(isset($data_albums)){ 
			echo '<h2>Albums de Facebook</h2>';
			foreach ($data_albums as $k => $v) {?>
				<a href="<?php echo base_url('facebook_test/get_user_album_photos/?access_token='.$access_token.'&ida='.$v->id) ?>" target="photos"><?php echo $v->name ?></a><br>
	<?php }	
		echo '<iframe src="" name="photos" style="width:900px; height:600px "></iframe>';
	} ?>

	<?php 
	if (isset($data_photos)) {
		echo '<h2>Fotos del Album</h2>';
		foreach ($data_photos as $k => $v) { ?>	
			<a href="<?php echo $v->images[0]->source ?>" target="_BLANK">
				<img src="<?php echo $v->picture?>">
			</a>
	<?php }}?>

</body>
</html>