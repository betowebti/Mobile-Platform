<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="VideoCtrl">
<?php
if(count($videos) > 0)
{
	if(count($videos) == 1)
	{
		// Show video
		if($videos[0]->code)
		{
			//if(isset($videos[0]->title) && trim($videos[0]->title) != '') echo '<h3>' . $videos[0]->title . '</h3>';
			echo '<div class="video-container">';
			echo $videos[0]->code;
			echo '</div>';
			if(isset($videos[0]->description) && trim($videos[0]->description) != '') echo '<div class="transparent"><p>' . $videos[0]->description . '</p></div>';
		}
	}
	else
	{
		// Show grid
		$i = 0;
		$slides = '';

		foreach($videos as $video)
		{
			if($video->code && $video->image)
			{
				// row responsive-md -> responsive
				$slides .= '<ion-slide><div class="video-container">' . $video->code . '</div></ion-slide>';
				if($i % 2 == 0) echo '<div class="row">';

				echo '<div class="col col-50">';
				echo '<div class="play"></div>';
				echo '<a href="javascript:void(0)" ng-click="openModal(\'' . $i . '\')"><img src="' . $video->image . '" width="100%"></a>';
				echo '</div>';

				$i++;
				if($i % 2 == 0) echo '</div>';
			}
		}
	}
}
?>
	</ion-content>
</ion-view>
<?php
if(count($videos) > 1)
{
?>
<script id="video-modal.html" type="text/ng-template">
  <div class="modal video-modal transparent" 
	   ng-click="closeModal()">
	<ion-slide-box on-slide-changed="slideChanged(index)" show-pager="false">
		{{ $slides }}
	</ion-slide-box>
  </div>
</script>
<?php
}
?>