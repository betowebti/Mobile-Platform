@extends('../app.layouts.partial')

@section('content')
	<ul class="breadcrumb breadcrumb-page">
		<div class="breadcrumb-label text-light-gray">{{ trans('global.you_are_here') }} </div>
		<li><a href="{{ trans('global.home_crumb_url') }}">{{ trans('global.home_crumb_text') }}</a></li>
		<li>{{ trans('global.content') }}</li>
		<li><a href="#/apps">{{ trans('global.apps') }}</a></li>
        <li class="active">{{ trans('global.app_users') }}</li>
	</ul>

	<div class="page-header">
		<div class="row">
			<h1 class="col-xs-12 col-sm-4 text-center text-left-sm" style="height:32px"><i class="fa fa-envelope-o page-header-icon"></i> {{ trans('global.app_users') }}</h1>

		</div>
	</div>

<script>
var public_users_table = $('#dt-table-public_users').DataTable({
    ajax: "{{ url('/api/v1/app-analytics/user-data') }}",
    order: [
        [0, "asc"],
        [4, "desc"]
    ],
    dom: "<'row'<'col-sm-12 dt-header'<'pull-left'lr><'pull-right'f><'pull-right hidden-sm hidden-xs'T><'clearfix'>>>t<'row'<'col-sm-12 dt-footer'<'pull-left'i><'pull-right'p><'clearfix'>>>",
    processing: true,
    serverSide: true,
    stateSave: true,
    stripeClasses: [],
    lengthMenu: [
        [10, 25, 50, 75, 100, 1000000],
        [10, 25, 50, 75, 100, "{{ trans('global.all') }}"]
    ],
    columns: [
    {
        data: "app"
    },
    {
        data: "email"
    },
    {
        data: "logins"
    },
    {
        data: "last_login"
    },
    {
        data: "created_at"
    },
    {
        data: "sl",
        sortable: false
    }],
	fnDrawCallback: function() {
		onDataTableLoad();
	},
	columnDefs: [
		{
			render: function (data, type, row) {
				return '<span data-moment="fromNowDateTime">' + data + '</span>';
			},
			targets: [3, 4] /* Column to re-render */
		},
		{
			render: function (data, type, row) {
				return '<div class="row-actions-wrap"><div class="text-right row-actions" data-sl="' + data + '">' + 
					'<a href="javascript:void(0);" class="btn btn-xs btn-danger row-btn-delete" data-toggle="tooltip" title="{{ trans('global.delete') }}"><i class="fa fa-trash"></i></a>' + 
					'</div></div>';
			},
			targets: 5 /* Column to re-render */
		}
	],
    language: {
        emptyTable: "{{ trans('global.empty_table') }}",
        info: "{{ trans('global.dt_info') }}",
        infoEmpty: "",
        infoFiltered: "(filtered from _MAX_ total entries)",
        thousands: "{{ trans('i18n.thousands_sep') }}",
        lengthMenu: "{{ trans('global.show_records') }}",
        processing: '<i class="fa fa-circle-o-notch fa-spin"></i>',
        paginate: {
            first: '<i class="fa fa-fast-backward"></i>',
            last: '<i class="fa fa-fast-forward"></i>',
            next: '<i class="fa fa-caret-right"></i>',
            previous: '<i class="fa fa-caret-left"></i>'
        }
    },
    oTableTools: {
        sSwfPath: "{{ url('/assets/swf/tabletools/copy_csv_xls_pdf.swf') }}",
        sRowSelect: "os",
        aButtons: [{
            "sExtends": "copy",
            "sButtonText": '<i class="fa fa-files-o"></i>'
        }, {
            "sExtends": "xls",
            "sFileName": "*.xls",
            "sButtonText": '<i class="fa fa-file-excel-o"></i>'
        }, {
            "sExtends": "pdf",
            "sButtonText": '<i class="fa fa-file-pdf-o"></i>'
        }]
    }
});

$('#dt-table-public_users_wrapper .dataTables_filter input').attr('placeholder', "{{ trans('global.search_') }}");

</script>
	<div class="table-primary">
		<table class="table table-striped table-bordered table-hover" id="dt-table-public_users">
			<thead>
				<tr>
					<th>{{ Lang::get('global.app') }}</th>
					<th>{{ Lang::get('global.email') }}</th>
					<th>{{ Lang::get('global.logins') }}</th>
					<th>{{ Lang::get('global.last_login') }}</th>
					<th>{{ Lang::get('global.created') }}</th>
					<th class="text-right" style="width:50px">{{ Lang::get('global.actions') }}</th>
				</tr>
			</thead>
		</table>
	</div>

<script>

$('#dt-table-public_users').on('click', '.row-btn-delete', function() {
    var sl = $(this).parent('.row-actions').attr('data-sl');

	swal({
	  title: _lang['confirm'],
	  type: "warning",
	  showCancelButton: true,
	  cancelButtonText: _lang['cancel'],
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: _lang['yes_delete']
	}, 
	function(){
		blockUI();
	
		var jqxhr = $.ajax({
			url: "{{ url('/api/v1/app-analytics/user-delete') }}",
			data: { sl: sl},
			method: 'POST'
		})
		.done(function(data) {
            if(data.result == 'success')
            {
    			public_users_table.ajax.reload();
            }
            else
            {
                swal(data.msg);
            }
		})
		.fail(function() {
			console.log('error');
		})
		.always(function() {
			unblockUI();
		});
	});
});

</script>

@stop