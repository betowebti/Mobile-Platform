<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">
<?php
if($vcard_data != '')
{
	$vcard_data = json_decode($vcard_data, true);

	echo '<a href="' . url('api/v1/widget/get/vcard/getDownload?sl=' . $sl ) . '" class="button button-large button-balanced button-full icon-left ion-ios-download-outline">' . $vcard_button . '</a>';

	$photo = (isset($vcard_data['photo'])) ? $vcard_data['photo'] : '';

	if($photo != '')
	{
		echo '<div class="card">';
		echo '<div class="item item-image">';
		echo '<a href="' . url('api/v1/widget/get/vcard/getDownload?sl=' . $sl ) . '">';
		echo '<img src="' . $photo . '" class="full-image">';
		echo '</a>';
		echo '</div>';
		echo '</div>';
	}
}
?>
	</ion-content>
</ion-view>