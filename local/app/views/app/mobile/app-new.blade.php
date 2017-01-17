@extends('../app.layouts.partial')
@section('content')
<ul class="breadcrumb breadcrumb-page">
  <div class="breadcrumb-label text-light-gray">{{ trans('global.you_are_here') }} </div>
  <li><a href="{{ trans('global.home_crumb_url') }}">{{ trans('global.home_crumb_text') }}</a></li>
  <li>{{ trans('global.content') }}</li>
  <li><a href="#/apps">{{ trans('global.apps') }}</a></li>
  <li class="active">{{ trans('global.new_app') }}</li>
</ul>
<div class="page-header">
  <h1 style="height:32px"><i class="fa fa-mobile page-header-icon"></i> {{ trans('global.new_app') }}</h1>
</div>
<div class="row">
  <div class="col-xs-12 col-md-8">
    <?php
      echo Former::open()
      	->class('form custom-validate')
      	->action(url('api/v1/app/new'))
      	->method('POST');
      ?>
    <div class="wizard ui-wizard" id="wizard-form">
      <div class="wizard-wrapper">
        <ul class="wizard-steps">
          <li data-target="#wizz-step1">
            <span class="wizard-step-number">1</span>
            <span class="wizard-step-caption">
            {{ trans('global.name') }}
            <span class="wizard-step-description">{{ trans('global.name_your_app') }}</span>
            </span>
          </li>
          <li data-target="#wizz-step2" onClick="setTimeout(verticalResizer, 300);">
            <!-- ! Remove space between elements by dropping close angle -->
            <span class="wizard-step-number">2</span>
            <span class="wizard-step-caption">
            {{ trans('global.type') }}
            <span class="wizard-step-description">{{ trans('global.choose_a_type') }}</span>
            </span>
          </li>
        </ul>
        <!-- / .wizard-steps -->
      </div>
      <!-- / .wizard-wrapper -->
      <div class="wizard-content panel">
        <div class="wizard-pane" id="wizz-step1">
          <?php
            echo Former::text()
                ->name('name')
                ->autocomplete('off')
                ->help(trans('global.app_name_info'))
            	->dataFvNotempty()
            	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
                ->placeholder(trans('global.untitled_app'))
            	->label(trans('global.name'));
            
            echo Former::text()
                ->name('campaign')
                ->useDatalist($campaigns, 'name')
                /*->value('{"id": "1", "text": "Store" }')*/
            	->class('select2-datalist form-control')
                ->autocomplete('off')
                ->help(trans('global.campaign_info'))
            	->dataFvNotempty()
            	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
            	->label(trans('global.campaign'));
            
            echo '<hr class="no-grid-gutter-h grid-gutter-margin-b no-margin-t">';
            
            echo Former::select('language')
            	->class('select2-required form-control')
                ->name('language')
                ->id('language')
                ->forceValue(Auth::user()->language)
            	->options(trans('languages.languages'))
            	->label(trans('global.language'));
            
            echo Former::select('timezone')
            	->class('select2-required form-control')
                ->name('timezone')
                ->forceValue(Auth::user()->timezone)
            	->options(trans('timezones.timezones'))
            	->label(trans('global.timezone'));
            ?>
          <div class="pull-right wizard-buttons">
            <a href="#/apps" class="btn btn-lg btn-default">{{ trans('global.cancel') }}</a>
            <button type="button" class="btn btn-lg btn-primary wizard-next-step-btn">{{ trans('global.next') }}</button>
          </div>
        </div>
        <!-- / .wizard-pane -->
        <div class="wizard-pane" id="wizz-step2" style="display: none;">
          <input type="hidden" name="app_type_id" id="app_type_id" value="">
          <input type="hidden" name="app_theme" id="app_theme" value="">
          <div class="row-fluid icon-select unselectable">
            <?php
              foreach($app_types as $type)
              {
              	$name = trans('global.' . $type['name']);
              ?>
            <div class="col-xs-6 col-md-4 col-lg-3">
              <div class="icon-select-item" data-id="{{ $type['id'] }}" data-theme="{{ $type['name'] }}" data-background="{{ url('/themes/' . $type['name'] . '/assets/img/background-phone.png') }}" data-icon="{{ url('/static/app-icons/' . $type['app_icon'] . '/120.png') }}">
                <div class="vertical-center">
                  <?php
                    if (substr($type['icon'], 0, 4) == '<svg')
                    {
                    	echo '<div style="width:' . $type['icon_width'] . 'px;margin:auto">' . $type['icon'] . '</div>';
                    }
                    else
                    {
                    ?>
                  <i class="fa {{ $type['icon'] }}"></i>
                  <?php
                    }
                    ?>
                </div>
                <div>
                  {{ $name }}
                </div>
              </div>
            </div>
            <?php
              }
              ?>
            <br style="clear:both">
          </div>
          <div class="pull-right wizard-buttons">
            <button type="button" class="btn btn-lg wizard-prev-step-btn">{{ trans('global.previous') }}</button>
            <button type="button" id="submit-form" class="btn btn-lg btn-primary">{{ trans('global.create_app') }}</button>
          </div>
        </div>
        <!-- / .wizard-pane -->
      </div>
      <!-- / .wizard-content -->
    </div>
    <!-- / .wizard -->
    <?php
      echo Former::close();
      ?>	
  </div>
  <div class="col-xs-12 col-md-4 text-center">
    <div class="marvel-device iphone6">
      <div class="top-bar"></div>
      <div class="sleep"></div>
      <div class="volume"></div>
      <div class="camera"></div>
      <div class="sensor"></div>
      <div class="speaker"></div>
      <div class="screen">
        <div id="app-icon"></div>
        <iframe src="{{ url('/mobile') }}" frameborder="0" id="device-screen"></iframe>
      </div>
      <div class="home"></div>
      <div class="bottom-bar"></div>
    </div>
    <br><br>
    <div class="btn-group dropup device-selector">
      <button type="button" class="btn btn-rounded dropdown-toggle" data-toggle="dropdown"><i class="fa fa-android fa-3x"></i></button>
      <ul class="dropdown-menu">
        <li data-phone="nexus5"><a href="javascript:void(0);">Nexus 5</a></li>
        <?php /*                    <li data-phone="lumia920"><a href="javascript:void(0);">Lumia 920</a></li>*/ ?>
        <li data-phone="s5"><a href="javascript:void(0);">Samsung Galaxy S5</a></li>
        <?php /*                    <li data-phone="htc-one"><a href="javascript:void(0);">HTC One</a></li>*/ ?>
      </ul>
    </div>
    <div class="btn-group dropup device-selector">
      <button type="button" class="btn btn-rounded btn-primary dropdown-toggle" data-toggle="dropdown"><i class="fa fa-apple fa-3x"></i></button>
      <ul class="dropdown-menu">
        <li data-phone="iphone6" class="active"><a href="javascript:void(0);">iPhone 6</a></li>
        <li data-phone="iphone5s"><a href="javascript:void(0);">iPhone 5S</a></li>
        <li data-phone="iphone5c"><a href="javascript:void(0);">iPhone 5C</a></li>
        <?php /*                    <li data-phone="iphone4s"><a href="javascript:void(0);">iPhone 4S</a></li>*/ ?>
      </ul>
    </div>
  </div>
</div>
<style type="text/css">
  #app-icon {
  width:100%;
  height:100%;
  position:absolute;
  background-color:transparent !important;
  background-image:url(static/app-icons/globe/120.png);
  background-position:center center;
  background-repeat:no-repeat;
  z-index:999;
  }
</style>
<script>
  /*
   * App builder device selector
   */
   
  $('.device-selector li').on('click', function() {
      var newClass = $(this).attr('data-phone');
  	setPreviewDevice(newClass);
  });
  
  if ($.cookie('previewDevice') != null)
  {
  	setPreviewDevice($.cookie('previewDevice'));
  }
  
  function setPreviewDevice(device)
  {
      $('.device-selector .btn-rounded').removeClass('btn-primary');
      $('.device-selector ul li').removeClass('active');
      $('.marvel-device').removeClass('iphone6 iphone5s iphone5c iphone4s nexus5 lumia920 s5 htc-one');
  
      $('.marvel-device').addClass(device);
      $('[data-phone=' + device + ']').addClass('active');
      $('[data-phone=' + device + ']').parent().prev().addClass('btn-primary').removeClass('btn-default');
  
  	$.cookie('previewDevice', device,
  	{
  		path: '/',
  		expires: 30
  	});
  }
  
  $('.icon-select-item').on('click', function() {
  	$('#app_type_id').val($(this).attr('data-id'));
  	$('#app_theme').val($(this).attr('data-theme'));
      $('.icon-select-item').removeClass('active');
      $(this).addClass('active');
  	$('#app-icon').css('background', 'url(' + $(this).attr('data-icon') + ') center center no-repeat');
  	$('#device-screen').contents().find('body').css('background', 'url(' + $(this).attr('data-background') + ') no-repeat');
  
  	/* Switch stylesheet */
  	$('#device-screen').contents().find('#themestyle').attr('href', 'themes/' + $(this).attr('data-theme') + '/assets/css/style.css');
  	$('#device-screen').contents().find('#themecustomstyle').attr('href', 'themes/' + $(this).attr('data-theme') + '/assets/css/custom.css');
  });
  
  $('.icon-select-item').on('dblclick', function() {
  	$('#app_type_id').val($(this).attr('data-id'));
  	$('#app_theme').val($(this).attr('data-theme'));
      $('.icon-select-item').removeClass('active');
      $(this).addClass('active');
  	$('#app-icon').css('background', 'url(' + $(this).attr('data-icon') + ') center center no-repeat');
  	$('#device-screen').contents().find('body').css('background', 'url(' + $(this).attr('data-background') + ') no-repeat');
  	$('#submit-form').trigger('click');
  });
  
  $('#name').on('keyup', function() {
  	$('#device-screen').contents().find('#app-title').text($(this).val());
  });
  
  var wiz = $('.ui-wizard').pixelWizard({
  	onChange: function () {
  		//console.log('Current step: ' + this.currentStep());
  	},
  	onFinish: function () {
  		// Disable changing step. To enable changing step just call this.unfreeze()
  		this.freeze();
  		//console.log('Wizard is freezed');
  		//console.log('Finished!');
  	}
  });
  
  $('.wizard-next-step-btn').click(function () {
  	setTimeout(verticalResizer, 300);
      var wizard_continue = true;
      var step = $('#wizard-form').pixelWizard('currentStep');
  
      if(step == 1)
      {
          var validate = $('form.custom-validate')
              .formValidation('validateField', 'name');
  
          var has_error = $(this).parents('.wizard-pane').find('.has-error').length;
  
          var campaign = ($('#campaign').val() != '') ? JSON.parse($('#campaign').val()).text : '';
  
  		// Check campaign
  		if(campaign.trim() == '')
  		{
  			has_error = true;
  			$('form #campaign').closest('.form-group').addClass('has-error');
  			$('form.custom-validate').bootstrapValidator('updateStatus', 'campaign', 'INVALID');
  		}
  		else
  		{
  			if(! has_error) has_error = false;
  			$('form #campaign').closest('.form-group').removeClass('has-error');
  			$('form.custom-validate').bootstrapValidator('updateStatus', 'campaign', 'VALID');
  		}
  
          if(has_error)
          {
              wizard_continue = false;
          }
      }
  
  	if(wizard_continue) $('#wizard-form').pixelWizard('nextStep');
  });
  
  // Catch enters
  $('input').keydown( function(e) {
  	var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
  	if(key == 13) {
  		e.preventDefault();
  
  		var step = $('#wizard-form').pixelWizard('currentStep');
  
  		if(step == 1)
  		{
  			$('.wizard-next-step-btn').trigger('click');
  		}
  	}
  });
  
  $('#submit-form').on('click', function(){
  	blockUI();
  	$('#pa-page-alerts-box').remove();
  	var app_type_id = $('#app_type_id').val();
  	if(app_type_id == '')
  	{
  	    unblockUI();
  		wizard_continue = false;
  		swal("{{ trans('global.select_type_alert') }}", null, 'warning');
  	}
  	else
      {
          ajaxSubmitForm($('form.custom-validate').attr('action'), $('form.custom-validate').serialize(), true);
      }
  });
  
  function formSubmittedSuccess(r)
  {
      if(r.result == 'error')
      {
          return;
      }
  
      // Increment App count
      var count = parseInt($('#count_apps').text());
      $('#count_apps').text(count+1);
  
  	unblockUI();
  
  	// Open App
  	document.location = '#/app/edit/' + r.sl;
  }
  
  $('.wizard-prev-step-btn').click(function () {
  	setTimeout(verticalResizer, 300);
  	$(this).parents('.ui-wizard').pixelWizard('prevStep');
  });
  
</script>
@stop