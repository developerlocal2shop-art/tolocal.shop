<html>
	<head>
		<title>Shopping Card Details</title>
			<?php $imagename = Helper::getShoppingcardimage($shoppingCardDetails[0]->id);?>
			<meta property="og:url" content="<?php echo env('APP_URL').'/share/shoping-card/'.$shoppingCardDetails[0]->id ?>"/>
			<meta property="og:type" content="website"/>
			<meta property="og:title" content="<?php echo $shoppingCardDetails[0]->title ?>"/>
			<meta property="og:description" content="<?php echo $productContent[0]->description ?>" />
			<meta property="og:image" content="<?php echo env('IMAGE_PATH').'images/shoppingcard/'.$imagename[0]->name_thumbnail.'.webp' ?>"/>
	</head>
	<body>
		
	</body>
</html>