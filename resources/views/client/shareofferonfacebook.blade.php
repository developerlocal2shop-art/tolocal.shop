<html>
	<head>
		<title>Shopping Card Details</title>
			<?php $imagename = Helper::getOfferimage($offerDetails[0]->id);?>
			<meta property="og:url" content="<?php echo env('APP_URL').'/share/offer-facebook-share/'.$offerDetails[0]->id ?>"/>
			<meta property="og:type" content="website"/>
			<meta property="og:title" content="<?php echo $offerDetails[0]->title ?>"/>
			<meta property="og:description" content="<?php echo $offerDetails[0]->description ?>" />
			<meta property="og:image" content="<?php echo env('IMAGE_PATH').'images/offer/'.$imagename[0]->name_thumbnail.'.webp' ?>"/>
	</head>
	<body>
		
	</body>
</html>