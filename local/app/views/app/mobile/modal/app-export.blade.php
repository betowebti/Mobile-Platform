<div class="modal-dialog" style="width:800px" id="export-modal-dialog">
<?php
echo Former::open()
  ->class('form-horizontal')
  ->action(url('api/v1/app-export/app-export'))
  ->id('frmExport')
  ->target('iSubmit')
  ->method('POST');

echo Former::hidden()
  ->value($sl)
  ->name('sl');

echo Former::hidden()
  ->value($pg_sl)
  ->name('pg_sl');
?>
  <div class="modal-content">
    <iframe src="about:blank" name="iSubmit" frameborder="0" style="display:none;width:0px;height:0px"></iframe>
    <div class="modal-header">
      <button class="close" type="button" data-dismiss="modal">×</button>
      <?php echo trans('export.download_app_', ['app_name' => $app->name]) ?>
    </div>
    <div class="modal-body" style="overflow:visible !important">

      <div class="jumbotron" style="padding:20px;margin-bottom:20px">
        <img src="{{ $app->icon(80); }}" class="pull-left" style="margin:0 20px 20px 0">
        <p class="lead no-margin">{{ trans('export.hero') }} <a href="javascript:void(0);" onclick="$('div#export_more').show();$(this).hide()">{{ trans('export.more') }}</a></p>
        <div style="display:none" id="export_more">
          <p class="lead no-margin"><br>{{ trans('export.explanation', ['url' => url('/')]) }}</p>
          <br>
          <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> {{ trans('export.warning_compatible_widgets') }}</div>
          <div class="alert alert-danger" style="margin-bottom:0"><i class="fa fa-exclamation-triangle"></i> {{ trans('export.warning_webserver') }}</div>

        </div>
      </div>

      <div class="form-group">
        <label for="sitemap_url" class="control-label col-lg-2 col-sm-4">{{ trans('export.sitemap_url') }} <span data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('export.sitemap_url_info') }}"><i class="fa fa-question-circle"></i></span></label>
        <div class="col-lg-10 col-sm-8"><input class="form-control" autocorrect="off" id="sitemap_url" type="text" name="sitemap_url" value="{{ $sitemap_url }}">
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-lg-2 col-sm-4" style="margin-top:4px">
          <span data-toggle="tooltip" data-placement="top" data-original-title="<?php echo ($phonegap) ? trans('export.build_help_phonegap') : trans('export.build_help'); ?>"><i class="fa fa-question-circle"></i></span>
        </label>
        <div class="col-lg-10 col-sm-8">

          <button type="submit" class="btn btn-mwp btn-lg" id="pg-build"><i class="fa fa-wrench"></i> <span id="build_btn">{{ $build_btn }}</span></button>
<?php if ($phonegap) { ?>
          <div class="help-block">{{ $pg_app->last_build }}</div>
<?php } ?>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-lg-2 col-sm-4" style="margin-top:6px">{{ trans('global.download') }} 
<?php if ($phonegap && 1==2) { ?>
          <span data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('export.download_help') }}"><i class="fa fa-question-circle"></i></span>
<?php } ?>
        </label>
        <div class="col-lg-10 col-sm-8">

          <div class="btn-group btn-group-lg" role="group" id="download_app">
            <a href="{{ url('api/v1/app-export/download/html5/' . $sl) }}" class="btn btn-lg btn-primary<?php echo ($html5) ? '' : ' disabled'; ?>" data-type="html5" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo trans('export.html5') ?>"><i class="fa fa-html5"></i></a>
            <a href="{{ url('api/v1/app-export/download/cordova/' . $sl) }}" class="btn btn-lg btn-primary<?php echo ($cordova) ? '' : ' disabled'; ?>" data-type="cordova" data-toggle="tooltip" data-placement="bottom" data-original-title="<?php echo trans('export.cordova') ?>">
<svg version="1.1"
   xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
   x="0px" y="0px" width="27.2px" height="26px" viewBox="0 0 27.2 26" style="enable-background:new 0 0 27.2 26;"
   xml:space="preserve">
<style type="text/css">
  .cordova-icon{fill:#FFFFFF;}
</style>
<defs>
</defs>
<g>
  <path class="cordova-icon" d="M24.8,26h-4.4l0.3-3.7h-2.2L18.2,26H9.1l-0.3-3.7H6.6L6.9,26H2.5L0,9.9L6.2,0H21l6.2,9.9L24.8,26z M19.8,4.9
    h-4l0.3,1.9h-4.9l0.3-1.9h-4L5,9.9l1.2,9.9H21l1.2-9.9L19.8,4.9z M17.6,16.5c-0.3,0-0.6-1-0.6-2.3c0-1.3,0.3-2.3,0.6-2.3
    s0.6,1,0.6,2.3S18,16.5,17.6,16.5z M9.8,16.7c-0.3,0-0.6-1-0.6-2.3s0.3-2.3,0.6-2.3s0.6,1,0.6,2.3S10.2,16.7,9.8,16.7z"/>
</g>
</svg>

</a>
<?php if ($phonegap) { ?>
<?php /*
            <div class="btn-group">
              <a href="javascript:void(0);" data-type="ios" class="btn btn-lg btn-{{ $btn_class['ios'] }}" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-apple"></i>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" aria-labelledby="dLabel">
                <li class="disabled"><a href="javascript:void(0);" id="key_ios">{{ $key_ios }}</a></li>
                <li><a href="javascript:void(0);" data-modal2="{{ url('app/modal/mobile/app-export/keys?platform=ios&sl=' . $sl) }}">{{ trans('export.select_certificate_') }}</a></li>
                <li role="separator" class="divider"></li>
                <li class="disabled" id="download-ios"><a href="javascript:void(0);"><?php echo trans('export.download_', ['download' => trans('export.ios_ipa')]) ?></a></li>
              </ul>
            </div>
*/ ?>
            <div class="btn-group">
              <a href="javascript:void(0);" data-type="android" class="btn btn-lg btn-{{ $btn_class['android'] }}" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-android"></i>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" aria-labelledby="dLabel">
<?php /*
                <li class="disabled"><a href="javascript:void(0);" id="key_android">{{ $key_android }}</a></li>
                <li><a href="javascript:void(0);" data-modal2="{{ url('app/modal/mobile/app-export/keys?platform=android&sl=' . $sl) }}">{{ trans('export.select_certificate_') }}</a></li>
                <li role="separator" class="divider"></li>
*/ ?>
                <li class="disabled" id="download-android"><a href="javascript:void(0);"><?php echo trans('export.download_', ['download' => trans('export.android_apk')]) ?></a></li>
              </ul>
            </div>

            <div class="btn-group">
              <a href="javascript:void(0);" data-type="winphone" class="btn btn-lg btn-{{ $btn_class['winphone'] }}" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-windows"></i>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" aria-labelledby="dLabel">
                <li class="disabled"><a href="javascript:void(0);" id="key_winphone">{{ $key_winphone }}</a></li>
                <li><a href="javascript:void(0);" data-modal2="{{ url('app/modal/mobile/app-export/keys?platform=winphone&sl=' . $sl) }}">{{ trans('export.select_publisher_id_') }}</a></li>
                <li role="separator" class="divider"></li>
                <li class="disabled" id="download-winphone"><a href="javascript:void(0);"><?php echo trans('export.download_', ['download' => trans('export.windows_phone_xap')]) ?></a></li>
              </ul>
            </div>
<?php } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal" type="button" id="close-button"><?php echo trans('global.close') ?></button>
    </div>
<?php
echo Former::close();
?>
  </div>
</div>
<style type="text/css">
#download_app .btn {
  font-size: 26px;
}
.modal-body {
  overflow: visible !important;
}

.pending {
  pointer-events:none;
  cursor:not-allowed;
}
.pending:before {
  content: "";
  display:inline-block;
  position: absolute;
  left:0;
  top:0;
  right:0;
  bottom:0;
  height:100%;
  width:100%;
  background-color:rgba(255, 255, 255, 0.5);

  -webkit-box-sizing:border-box;
  -moz-box-sizing:border-box;
  -ms-box-sizing:border-box;
  box-sizing:border-box;
  background-image:
    -webkit-linear-gradient(
    -45deg,
    rgba(0, 0, 0, 0.2) 25%,
    transparent 25%,
    transparent 50%,
    rgba(0, 0, 0, 0.2) 50%,
    rgba(0, 0, 0, 0.2) 75%,
    transparent 75%,
    transparent
  );
  background-image:
    -moz-linear-gradient(
    -45deg,
    rgba(0, 0, 0, 0.2) 25%,
    transparent 25%,
    transparent 50%,
    rgba(0, 0, 0, 0.2) 50%,
    rgba(0, 0, 0, 0.2) 75%,
    transparent 75%,
    transparent
  );
  background-image:
    -ms-linear-gradient(
    -45deg,
    rgba(0, 0, 0, 0.2) 25%,
    transparent 25%,
    transparent 50%,
    rgba(0, 0, 0, 0.2) 50%,
    rgba(0, 0, 0, 0.2) 75%,
    transparent 75%,
    transparent
  );
  background-image:
    linear-gradient(
    -45deg,
    rgba(0, 0, 0, 0.2) 25%,
    transparent 25%,
    transparent 50%,
    rgba(0, 0, 0, 0.2) 50%,
    rgba(0, 0, 0, 0.2) 75%,
    transparent 75%,
    transparent
  );
  -webkit-background-size:50px 50px;
  -moz-background-size:50px 50px;
  -ms-background-size:50px 50px;
  background-size:50px 50px;
  -webkit-animation:move 2s linear infinite;
  -moz-animation:move 2s linear infinite;
  -ms-animation:move 2s linear infinite;
  animation:move 2s linear infinite;

  overflow: hidden;
  -webkit-box-shadow:inset 0 10px 0 rgba(255,255,255,.2);
  -moz-box-shadow:inset 0 10px 0 rgba(255,255,255,.2);
  -ms-box-shadow:inset 0 10px 0 rgba(255,255,255,.2);
  box-shadow:inset 0 10px 0 rgba(255,255,255,.2);
}

.btn-default .cordova-icon {
  fill: #555;
}

/*
Animate the stripes
*/   
@-webkit-keyframes move{
  0% {
  background-position: 0 0;
  }
  100% {
  background-position: 50px 50px;
  }
}  
@-moz-keyframes move{
  0% {
  background-position: 0 0;
  }
  100% {
  background-position: 50px 50px;
  }
}  
@-ms-keyframes move{
  0% {
  background-position: 0 0;
  }
  100% {
  background-position: 50px 50px;
  }
}  
@keyframes move{
  0% {
  background-position: 0 0;
  }
  100% {
  background-position: 50px 50px;
  }
}  

</style>
<script>
$('[data-toggle~=tooltip]').tooltip(
{
  container: '#export-modal-dialog'
});

$('#frmExport').on('submit', function() {
  /*blockUI();*/
  $('#close-button,#pg-build,.close').prop('disabled', true);

  $('#download_app [data-type]').removeClass('btn-default btn-primary btn-danger btn-warning');
  $('#download_app [data-type]').addClass('disabled pending btn-default');
});

function submitReady(pg_sl)
{
  if (typeof pg_sl !== 'undefined')
  {
    $('#pg_sl').val(pg_sl);
  }

  $('#build_btn').text("{{ trans('export.rebuild') }}");

  $('#close-button,#pg-build,.close').prop('disabled', false);
  $('#download_app .btn').filter('[data-type=html5],[data-type=cordova]').removeClass('disabled pending btn-default').addClass('btn-primary');

<?php if ($phonegap) { ?>
  getBuildStatus();
<?php } ?>
}

<?php if ($phonegap) { ?>

getBuildStatus();

function getBuildStatus(){
  var jqxhr = $.ajax({
    type: 'POST',
    url: "{{ url('api/v1/app/app-export-get-build-status') }}",
    data: { sl: "{{ $sl }}" },
    dataType: 'json',
    cache: false
  })
  .done(function(data) {
 
    $('[data-type=android]').removeClass('disabled pending btn-default btn-primary btn-danger btn-warning');
    $('[data-type=android]').addClass(data.android.status_class + ' ' + data.android.pending_class + ' ' + data.android.download_class);
    $('#download-android').removeClass('disabled');
    $('#download-android').addClass(data.android.download_class_href);
    $('#download-android a').attr('href', data.android.download);
<?php /*
    $('[data-type=ios]').removeClass('disabled pending btn-default btn-primary btn-danger btn-warning');
    $('[data-type=ios]').addClass(data.ios.status_class + ' ' + data.ios.pending_class + ' ' + data.ios.download_class);
    $('#download-ios').removeClass('disabled');
    $('#download-ios').addClass(data.ios.download_class_href);
    $('#download-ios a').attr('href', data.ios.download);
*/ ?>
    $('[data-type=winphone]').removeClass('disabled pending btn-default btn-primary btn-danger btn-warning');
    $('[data-type=winphone]').addClass(data.winphone.status_class + ' ' + data.winphone.pending_class + ' ' + data.winphone.download_class);
    $('#download-winphone').removeClass('disabled');
    $('#download-winphone').addClass(data.winphone.download_class_href);
    $('#download-winphone a').attr('href', data.winphone.download);

    if (! data.ready && data.built)
    {
      getBuildStatus();
    }
    else
    {
      $('#close-button,#pg-build,.close').prop('disabled', false);
    }

    if (! data.built)
    {
      $('[data-type=android],[data-type=ios],[data-type=winphone]').removeClass('disabled pending btn-default btn-primary btn-danger btn-warning');
      $('[data-type=android],[data-type=ios],[data-type=winphone]').addClass('btn-primary');

    }
  })
  .fail(function() {
    console.log('Error changing layout');
  })
  .always(function() {

  });
}

<?php } else { ?>

function getstatus() {
  $.ajax({
    url: "{{ url('api/v1/app-export/app-export-status') }}",
    type: "POST",
    dataType: 'json',
    success: function(data) {
      if (typeof data.status === 'undefined' || data.status == 'pending')
      {
        setTimeout(function() { getstatus(); }, 1000);
      }
      else
      {
        $('#close-button,#pg-build,.close').prop('disabled', false);
        $('#download_app .btn').filter('[data-type=html5],[data-type=cordova]').removeClass('disabled pending');
      }
    }
  });
}
<?php } ?>

/*
 * Bootstrap Modal
 */
var $modal2 = $('#ajax-modal2');

$('[data-modal2]').on('click', function()
{
  $('body').modalmanager('loading');

  $modal2.load($(this).attr('data-modal2'), '', function()
  {
    $modal2.modal();
    onModalLoad();
  });
});
</script>