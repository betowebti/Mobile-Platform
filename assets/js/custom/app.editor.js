var elfinderUrl = 'elfinder/standalonepopup/';

/*
 * Launch help tour, first check if cookie exists
 */

appLaunchEditorTour = function()
{
	$.getJSON(app_root + '/api/v1/help/editor' /* + app_lang*/ , function(data)
	{
		hopscotch.configure(
		{
			onEnd: function()
			{
				$.cookie('tourEditor', '1',
				{
					path: '/',
					expires: 30
				});
			},
			onClose: function()
			{
				$.cookie('tourEditor', '1',
				{
					path: '/',
					expires: 30
				});
			}
		});

		hopscotch.startTour(data);

		$(document).click(function(e) {
			var target = e.target;
		
			if (!$(target).is('.hopscotch-bubble') && !$(target).is('.hopscotch-nav-button') && !$(target).parents().is('.hopscotch-bubble')) {
				hopscotch.endTour(false);
			}
		});
	});
}

/*
$('.marvel-device').affix({
	offset: {
		top: 117
	}
});
*/

if ($.cookie('tourEditor') != '1')
{
	setTimeout(function() {
		appLaunchEditorTour();
	}, 500);
}


function reloadPreview(redir_to_home)
{
	var redir_to_home = (typeof redir_to_home === 'undefined' || redir_to_home == false) ? false : true;

	document.getElementById('device-screen').contentDocument.location.reload(true);
}

function owlAfterInit(id)
{
	if(id == 'carousel_pages')
	{
		$('#carousel_pages').find('.ui-state-disabled').parents('.owl-item').addClass('ui-state-disabled');
		$('#carousel_pages .owl-item').each(function() {
			var id = $(this).find('.app-page-container').attr('data-id');
			$(this).attr('data-id', id)
		});
	}
}

$('#carousel_pages').sortable({
	items: '.owl-item:not(.ui-state-disabled)',
	handle: '.app-page-drag-handle',
    tolerance: 'pointer', /* intersect */
    scrollSensitivity: 100,
    update: function (event, ui) {
        /*blockUI();*/
		var node = $(ui.item).attr('data-id');
		var node_prev = $(ui.item).prev('.owl-item').attr('data-id');
		var node_next = $(ui.item).next('.owl-item').attr('data-id');
        /*var sort = $(this).sortable('toArray', {attribute: 'data-id'});*/

        $.ajax({
            data: {node: node, node_prev: node_prev, node_next: node_next, sl: sl},
            type: 'POST',
            url: app_root + "/api/v1/app-edit/page-sort"
        })
        .done(function(data) {
			reloadPreview();
			$('#carousel_pages').data('owlCarousel').reinit();
			showSaved();
        })
        .always(function() {
            /*unblockUI();*/
        });
    },
    /*
    containment: 'parent',*/
    placeholder: 'ui-state-highlight',
	axis: 'x',
    distance: 5
});

/* Change layout */
$('#app-layouts').on('click', '.app-border-selection-ul li', function(event) {
  if(event.handled !== true)
  {
	blockUI('.screen');
	var layout = $(this).attr('data-layout');
    var jqxhr = $.ajax({
	  type: 'POST',
	  url: app_root + "/api/v1/app-edit/layout",
	  data: { sl: sl, layout: layout },
	  cache: false
	})
	.done(function(data) {
		$('#app-layouts li').removeClass('active');
		$('#app-layouts li[data-layout="' + layout + '"]').addClass('active');
		reloadPreview();
		showSaved();
	})
	.fail(function() {
	  console.log('Error changing layout');
	})
	.always(function() {
		unblockUI('.screen');
	});
    event.handled = true;
  }
  return false;

});

$('#carousel_pages').on('click', 'li:not(.app-page-drag-handle):not(.ui-state-disabled)', function(event) {
  if(event.handled !== true)
  {
	if($(this).hasClass('selected'))
	{
		/* deselect */
		deselectPage();
	}
	else
	{
		$('#carousel_pages li').removeClass('selected');
		$(this).addClass('selected');
	    var sl_page = $(this).find('.app-page-container').attr('data-sl');
	    var slug = $(this).find('.app-page-container').attr('data-slug');

	    var color = $(this).find('.app-page-container').attr('data-color');
		$('#app-pages').css({'border-color' : '#' + color});
		$('.app-page-selected').css({'background-color' : '#' + color});

		loadPage(sl_page, slug);
	}
    event.handled = true;
  }
  return false;
});

function deselectPage()
{
	$('#carousel_pages li').removeClass('selected');
	$('#page-content, #page-general-tab').hide();
	$('#app-pages').css({'border-color' : '#5bc0de'});
	$('.app-page-selected').css({'background-color' : '#5bc0de'});
}

function loadPage(sl_page, slug)
{
	$('#page-new').hide();
    $('#page-content, #page-general-tab').show();

    getAppPageContent(sl_page, 'page-general-tab');
    getAppPageContent(sl_page, 'page-content-tab');
    getAppPageContent(sl_page, 'page-design-tab');

	document.getElementById('device-screen').contentDocument.location = app_root + '/mobile/' + local_domain + '#' + hashPrefix + '/nav/' + slug;
}

function getAppPageContent(sl_page, id)
{
	if(id == 'page-general-tab')
	{
		$('#app_page_name').val('');
		$('#page-icon i').removeClass();
	}
	else
	{
	    $('#' + id).html('<div class="spinner" id="spinner" style="margin:10px auto"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div>');
	}

    var jqxhr = $.ajax({
	  type: 'GET',
	  url: app_root + "/api/v1/app-edit/page",
	  data: { sl: sl_page, id: id },
	  cache: false
	})
	.done(function(data) {
		$('#' + id).html(data);
	})
	.fail(function() {
	  console.log('Error loading page info: ' + url);
	})
	.always(function() {
	});
}

$('#carousel_pages').on('click', '.app-page-new', function(event) {
  if(event.handled !== true)
  {
    $('#carousel_pages li').removeClass('selected');
    $(this).parent().parent().addClass('selected');
    $('#page-content, #page-general-tab').hide();
	$('#page-new').show();
	$('#app-pages').css({'border-color' : '#5bc0de'});
	$('.app-page-selected').css({'background-color' : '#5bc0de'});

    event.handled = true;
  }
  return false;
});

$('#page-new').on('click', '.app-page-icon-holder:not(.widget-upgrade)', function(event) {
  if(event.handled !== true)
  {
    /*blockUI();*/

    var jqxhr = $.ajax({
          type: 'POST',
          url: app_root + "/api/v1/app-edit/page",
          data: { sl: sl, widget: $(this).attr('data-widget') },
          cache: false
        })
        .done(function(data) {

			if(data.result == 'error')
			{
				swal(
				{
					title: data.msg,
					type: "error"
				});
			}
			else
			{
				$('#carousel_pages li').removeClass('selected');
				$('#page-content, #page-general-tab').hide();
				$('#page-new').hide();

				content = '<li class="selected">' + 
				'<div class="app-page-container" data-sl="' + data.sl + '" data-slug="' + data.slug + '" data-color="' + data.color + '" data-id="' + data.id + '">' + 
				'<div class="app-page-drag-handle"></div>' + 
				'<div class="app-page-icon-holder bg-' + data.color + '" id="app-page1">' + 
				'<div class="app-page-icon sprite-xs xs-sprite-' + data.icon + '"> </div>' + 
				'<div class="app-page-title ellipsis">' + data.name + '</div>' + 
				'</div>' + 
				'<div class="app-page-selected"></div>' + 
				'</div>' + 
				'</li>';

				var page_count = $('#carousel_pages .owl-item').length;
				$('#carousel_pages').data('owlCarousel').addItem(content, page_count - 1);
				$('#carousel_pages').trigger('owl.goTo', page_count - 1)

				$('#carousel_pages .owl-item:nth-child(' + (page_count) + ')').attr('data-id', data.id);

				multiLineEllipsis();

				var page_count = parseInt($('#app-page-count').text());
				$('#app-page-count').text(page_count + 1)

				$('#app-pages').css({'border-color' : '#' + data.color});
				$('.app-page-selected').css({'background-color' : '#' + data.color});

				/* Reload page before loadPage because otherwise route doesn't exist yet */
				document.getElementById('device-screen').contentDocument.location.reload(true);
				setTimeout(function() {
					loadPage(data.sl, data.slug);
				}, 1000);

				showSaved();
			}
        })
        .fail(function() {
          console.log('Error loading page info: ' + url);
        })
		.always(function() {
			/*unblockUI();*/
		});

    event.handled = true;
  }
  return false;
});

$('#carousel_themes .app-border-container').on('click', function() {
	blockUI('.screen');
	var theme = $(this).attr('data-theme');

    var jqxhr = $.ajax({
	  type: 'POST',
	  url: app_root + "/api/v1/app-edit/theme",
	  data: { sl: sl, theme: theme },
	  cache: false
	})
	.done(function(data) {
		$('#carousel_themes .app-border-container').removeClass('active');
		$('#carousel_themes .app-border-container[data-theme="' + theme + '"]').addClass('active');

        var custom = $('#bg-app').attr('data-custom');

		if(custom == 0)
		{
			$('#bg-app').css('background-image', 'url("' + app_root + '/themes/' + theme + '/assets/img/background-phone.png")');
		}
		$('#bg-app').attr('data-theme', theme);
		$('#bg-app').attr('data-default', app_root + '/themes/' + theme + '/assets/img/background-phone.png');
		reloadPreview();
		showSaved();
	})
	.fail(function() {
	  console.log('Error changing layout');
	})
	.always(function() {
		unblockUI('.screen');
	});
});

$(document).on({
    mouseenter: function () {
		var theme = $(this).attr('data-theme');
		var theme_preview = app_root + '/themes/' + theme + '/assets/img/preview.png';
		var theme_preview_bg = app_root + '/themes/' + theme + '/assets/img/preview-bg.png';

        $(this).find('img').attr('src', theme_preview_bg);
    },

    mouseleave: function () {
		var theme = $(this).attr('data-theme');
		var theme_preview = app_root + '/themes/' + theme + '/assets/img/preview.png';
		var theme_preview_bg = app_root + '/themes/' + theme + '/assets/img/preview-bg.png';

        $(this).find('img').attr('src', theme_preview);
    }
}, '[data-theme]');

$('#carousel_themes .app-border-container').on('hover', function() {
	var theme = $(this).attr('data-theme');
alert(theme);
});

$('body').on('click', '.select-image', function(event)
{
  if(event.handled !== true)
  {
	// trigger the reveal modal with elfinder inside
	$.colorbox(
	{
		href: elfinderUrl + $(this).attr('data-id') + '/processAppFile',
		fastIframe: true,
		iframe: true,
		width: '70%',
		height: '80%'
	});
    event.handled = true;
  }
  return false;
});

$('body').on('click', '#select-app-bg', function(event)
{
  if(event.handled !== true)
  {
    $('#select-app-bg i').removeClass('fa-folder-open-o fa-folder-o');

    if($('#app-bg-selection').css('display') == 'block')
    {
    	$('#app-bg-selection').slideUp();
        $('#select-app-bg i').addClass('fa-folder-o');
    }
    else
    {
    	$('#app-bg-selection').slideDown();
        $('#select-app-bg i').addClass('fa-folder-open-o');
    }
    event.handled = true;
  }
  return false;
});

$('body').on('click', '#remove-app-bg,#select-no-app-bg', function(event)
{
  if(event.handled !== true)
  {
	blockUI('.screen');
	var none = ($(this).attr('id') == 'select-no-app-bg') ? 1 : 0;
    var request = $.ajax(
    {
        url: app_root + '/api/v1/app-edit/bg-app-image-remove',
        type: 'POST',
        data:
        {
            sl: sl,
            none: none
        },
        dataType: 'json'
    });

    request.done(function(json)
    {
        if(none == 1)
		{
			$('#remove-app-bg').show();
			$('#bg-app').css('background-image', 'url("' + app_root + '/assets/images/interface/1x1.gif")');
		}
		else
		{
			$('#remove-app-bg').hide();
			$('#bg-app').css('background-image', 'url("' + $('#bg-app').attr('data-default') + '")');
			$('#bg-app').attr('data-custom', 1);
		}
		unblockUI('.screen');
        reloadPreview();
        showSaved();
    });

    request.fail(function(jqXHR, textStatus)
    {
        console.log('Request failed, please try again (' + textStatus + ')');
		unblockUI('.screen');
    });

    event.handled = true;
  }
  return false;
});

$('body').on('click', '#remove-app-icon', function(event)
{
  if(event.handled !== true)
  {
	blockUI();

    var request = $.ajax(
    {
        url: app_root + '/api/v1/app-edit/custom-app-icon-remove',
        type: 'POST',
        data:
        {
            sl: sl
        },
        dataType: 'json'
    });

    request.done(function(json)
    {
        $('#remove-app-icon').hide();
        $('#app-icon').css('background-image', 'none');
        $('#app-icon').attr('data-custom', 0);
        $('#app-icon').removeClass('filled');
        $('#app-icon-top').attr('src', $('#app-icon').attr('data-original'));

		unblockUI();
        reloadPreview();
        showSaved();
    });

    request.fail(function(jqXHR, textStatus)
    {
        console.log('Request failed, please try again (' + textStatus + ')');
		unblockUI();
    });

    event.handled = true;
  }
  return false;
});

$('body').on('click', '#app-bg-selection .thumbnail', function(event) {
  if(event.handled !== true)
  {
	blockUI('.screen');

	var img = $(this).attr('data-img');
    var request = $.ajax(
    {
        url: app_root + '/api/v1/app-edit/bg-app-image',
        type: 'POST',
        data:
        {
            sl: sl,
            image: img
        },
        dataType: 'json'
    });

    request.done(function(json)
    {
		$('#bg-app').css('background-image', 'url("' + img + '")');
        $('#bg-app').attr('data-custom', 1);
        $('#remove-app-bg').show();
		unblockUI('.screen');
        reloadPreview();
        showSaved();
    });

    request.fail(function(jqXHR, textStatus)
    {
        console.log('Request failed, please try again (' + textStatus + ')');
		unblockUI('.screen');
    });

    event.handled = true;
  }
  return false;
});

$('.bs-x-text').editable({
	url: app_root + '/api/v1/app-edit/app-title',
    ajaxOptions: {
        type: 'post'
    },
	success: function(response, newValue) {
        if(response.status == 'error') return response.msg;
		showSaved();
    }
});


// Callback after elfinder selection
window.processAppFile = function(filePath, requestingField)
{
	blockUI();
	$('#' + requestingField).css('background-image', 'url("' + decodeURI(filePath) + '")');
	$('#' + requestingField).addClass('filled');

    if (requestingField == 'bg-app')
    {
        var url = app_root + '/api/v1/app-edit/bg-app-image';
    }
    else if (requestingField == 'app-icon')
    {
        var url = app_root + '/api/v1/app-edit/custom-app-icon';
    }

    var request = $.ajax(
    {
        url: url,
        type: 'POST',
        data:
        {
            sl: sl,
            image: filePath
        },
        dataType: 'json'
    });

    request.done(function(json)
    {
        if (requestingField == 'bg-app')
        {
            $('#remove-app-bg').show();
        }
        else if (requestingField == 'app-icon')
        {
            $('#remove-app-icon').show();
            $('#app-icon-top').attr('src', json.icon40);
            $('#app-icon').attr('data-custom', 1);
        }
		unblockUI();
        reloadPreview();
        showSaved();
    });

    request.fail(function(jqXHR, textStatus)
    {
		unblockUI();
        alert('Request failed, please try again (' + textStatus + ')');
    });
}

$('body').on('click', '#app-icon-picker-content a', function(event) {
  if(event.handled !== true)
  {
	blockUI();
	var icon = $(this).attr('data-icon');
    var request = $.ajax(
    {
        url: app_root + '/api/v1/app-edit/app-icon',
        type: 'POST',
        data:
        {
            sl: sl,
            icon: icon
        },
        dataType: 'json'
    });

    request.done(function(json)
    {
		$('#app-icon-picker').popover('hide');
		$('#app-icon-picker img').attr('src', app_root + '/static/app-icons/' + icon + '/120.png');
		$('#app-icon').attr('data-original', app_root + '/static/app-icons/' + icon + '/40.png');
        if ($('#app-icon').attr('data-custom') == 0)
        {
    		$('#app-icon-top').attr('src', app_root + '/static/app-icons/' + icon + '/40.png');
        }

		unblockUI();
        showSaved();
    });

    request.fail(function(jqXHR, textStatus)
    {
        alert('Request failed, please try again (' + textStatus + ')');
		unblockUI();
    });

    event.handled = true;
  }
  return false;
});

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