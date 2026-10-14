<html>
	<head>
		<title>Shop Details</title>
			<?php $imagename = Helper::getProductImage($data['productDetails'][0]->id);?>
			<meta property="og:url" content="<?php echo env('APP_URL').'/share/product/'.$data['productDetails'][0]->id ?>"/>
			<meta property="og:type" content="website"/>
			<meta property="og:title" content="<?php echo $productContent[0]->name ?>"/>
			<meta property="og:description" content="<?php echo $productContent[0]->short_content ?>" />
			<meta property="og:image" content="<?php echo env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' ?>"/>
			
			<meta name="twitter:card" content="tolocalshop">
			<meta name="twitter:site" content="tolocal.shop">
			<meta name="twitter:creator" content="TOLOCALSHOP">
			<meta name="twitter:title" content="<?php echo $productContent[0]->name ?>">
			<meta name="twitter:description" content="<?php echo $productContent[0]->short_content ?>">
			<meta name="twitter:image:src" content="<?php echo env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' ?>">
	</head>
	<body>
		
	</body>
</html>