<div class="modal-dialog" style="width:800px">
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			<?php echo trans('global.redirect_mobile_visitors') ?>
        </div>
		<div class="modal-body">

			<div class="jumbotron" style="padding:20px;margin-bottom:20px">
				<img src="{{ $app->icon(80); }}" class="pull-left" style="margin:0 20px 20px 0">
				<p class="lead no-margin">{{ trans('global.redirect_mobile_visitors_text') }}</p>
			</div>

			<textarea class="form-control" rows="10"><!-- {{ trans('global.redirect_mobile_visitors') }} -->
<script type="text/javascript" src="{{ str_replace('http:', '', url('/static/scripts/redirection-mobile.js')) }}"></script>
<script type="text/javascript">
SA.redirection_mobile ({
	mobile_url: "{{ str_replace(['http://', 'https://'], '', $app->domain()) }}",
	tablet_redirection: "false",
	noredirection_param: "noredirection",
	cookie_hours: "2"
});
</script></textarea>

		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" type="button"><?php echo trans('global.close') ?></button>
		</div>
	</div>
</div>