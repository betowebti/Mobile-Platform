<ion-view title="{{ $page->name }}">
  <?php
if($payment_provider_email != '')
{
?>
  <ion-nav-buttons side="{{ ($app->layout == 'side-right') ? 'primary': 'secondary'; }}">
    <button class="button icon-left" ng-click="checkoutService.showModal()"> <i class="icon ion-bag"></i> </button>
    &nbsp;
    <button class="button {{ $simpleCart }}_total shop_price" ng-click="checkoutService.showModal()"></button>
  </ion-nav-buttons>
  <?php
}
?>
  <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="eCommerceCtrl">
    <?php

if($sandbox)
{
	echo '<div class="transparent">';
	echo trans('widget::global.sandbox_alert');
	echo '</div>';
}


if($payment_provider_email == '')
{
	echo '<div class="transparent">';

	if($payment_provider_email == '')
	{
		echo trans('widget::global.payment_settings_missing');
	}

	echo '</div>';
}
else
{
	if($shop_title != '' && $shop_desc !='')
	{
		echo '<div class="transparent">';
		if($shop_title != '') echo '<h3>' . $shop_title . '</h3>';
		if($shop_desc != '') echo '<p>' . $shop_desc . '</p>';
		echo '</div>';
	}
	
	echo '<div class="list">';
	
	$i = 0;
	foreach($products as $product)
	{
		$class = ($images_found) ? ' item-avatar' : '';
		echo '<a href="javascript:void(0);" ng-click="showDetails(\'' . $simpleCart . '-' . $i . '\')" class="item item-text-wrap' . $class . ' item-button-right">';
		if($product['photo'] != '') echo '<img src="' . $product['thumb'] . '">';
		echo '<h2>' . $product['title'] . '</h2>';
		echo '<p class="item_price ">' . $product['price'] . '</p>';
		echo '	<div class="button button-positive" style="margin-top:12px"><i class="icon ion-search"></i></div> ';
		echo '</a>';
	
		$i++;
	}
	
	echo '</div>';
	
	// Modals
	$i = 0;
	foreach($products as $product)
	{
	?>
    <script id="product{{ $simpleCart }}-{{ $i }}.html" type="text/ng-template">
	  <ion-modal-view class="{{ $simpleCart }}_shelfItem">
		<ion-header-bar>
		  <h1 class="title">{{ $product['title'] }}</h1>
		  <div class="button button-clear" ng-click="modal.hide()"><span class="icon ion-close"></span></div>
		</ion-header-bar>
		<ion-content>
	
		<div class="item tabs tabs-secondary dark tabs-icon-left">
			<a class="tab-item" href="javascript:void(0);" ng-click="checkoutService.showModal()">
			  <i class="icon ion-bag"></i>
			  {{ trans('widget::global.checkout') }} (<span class="{{ $simpleCart }}_quantity"></span>)
			</a>
			<label class="tab-item item-input">
			  {{ trans('widget::global.quantity') }}
			  <input type="number" value="1" min="1" class="item_Quantity" style="text-align:center;width:100%">
			</label>
			<a class="tab-item item_add" href="javascript:alert('{{ trans('widget::global.item_added') }}');">
			  <i class="icon ion-ios-cart"></i>
			  {{ trans('widget::global.add_to_basket') }}
			</a>
		</div>
	<?php
	echo '<div class="item_price badge badge-calm" style="float:right;margin:15px">' . $product['price'] . '</div>';
	echo '<div class="padding">';
	echo '<h3 class="item_name">' . $product['title'] . '</h3>';
	if($product['desc'] != '') echo '<p>' . $product['desc'] . '</p>';
	if($product['photo'] != '') echo '<img src="' . $product['photo'] . '" class="full-image">';
	echo '</div>';
	?>
		</ion-content>
	  </ion-modal-view>
	</script>
    <?php
		$i++;
	}
	?>
    <script id="checkout.html" type="text/ng-template">
	<ion-modal-view>
		<ion-header-bar>
			<h1 class="title">{{ trans('widget::global.cart') }}</h1>
			<div class="button button-clear" ng-click="modal.hide()">
				<span class="icon ion-close"></span>
			</div>
		</ion-header-bar>
		<ion-content>
			<div class="padding">
				<div class="{{ $simpleCart }}_items"></div>
				<div class="{{ $simpleCart }}_totals">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td>
									{{ trans('widget::global.subtotal') }}:
								</td>
								<td width="130">
									<span class="{{ $simpleCart }}_total shop_price"></span>
								</td>
							</tr>
							<tr>
								<td>
									{{ trans('widget::global.tax') }} (<span class="{{ $simpleCart }}_taxRate taxRate"></span>%):
								</td>
								<td>
									<span class="{{ $simpleCart }}_tax shop_price"></span>
								</td>
							</tr>
							<tr>
								<td>
									{{ trans('widget::global.shipping') }}:
								</td>
								<td>
									<span class="{{ $simpleCart }}_shipping shop_price"></span>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<hr>
									</td>
								</tr>
								<tr>
									<td>
									{{ trans('widget::global.total') }}:
								</td>
									<td>
										<span class="{{ $simpleCart }}_grandTotal shop_price"></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				<button class="button button-balanced icon-left button-block {{ $simpleCart }}_checkout">
					{{ trans('widget::global.proceed_to_payment') }} <i class="icon ion-arrow-right-c"></i>
				</button>
				</div>
			</ion-content>
		</ion-modal-view>
	</script> 
    <script>
	simpleCart({
		checkout: {
			type: "{{ $payment_provider }}",
			email: "{{ $payment_provider_email }}",
			sandbox: <?php echo ($sandbox) ? 'true' : 'false'; ?>,
			method: "GET", 
			success: "{{ url('api/v1/widget/get/e-commerce/paymentSuccess?sl=' . $sl) }}", 
			cancel: "{{ url('api/v1/widget/get/e-commerce/paymentCancel?sl=' . $sl) }}"
		},
		cartStyle: "table",
		currency: "{{ $currency }}",
		/* collection of arbitrary data you may want to store with the cart, 
		such as customer info 
		data: {},*/
		language: "{{ $app->language }}",
		excludeFromCheckout: [],
		/* custom function to add shipping cost */
		shippingCustom: null,
		shippingFlatRate: {{ $flat_rate }},
		shippingQuantityRate: {{ $quantity_rate }},
		shippingTotalRate: {{ $total_rate }},
		taxRate: {{ $tax_rate / 100 }},
		taxShipping: <?php echo ($tax_shipping) ? 'true' : 'false'; ?>,
		beforeAdd               : null,
		afterAdd                : null,
		load                    : null,
		beforeSave              : null,
		afterSave               : null,
		update                  : null,
		ready                   : null,
		checkoutSuccess         : null,
		checkoutFail            : null,
		beforeCheckout          : null,
		cartColumns: [
			{ attr: "name" , label: "{{ trans('widget::global.title') }}" } ,
			{ attr: "price" , label: "{{ trans('widget::global.price') }}", view: 'currency' } ,
			{ view: "decrement" , label: false , text: '<span class="button button-small">-</span>' } ,
			{ attr: "quantity" , label: "{{ trans('widget::global.quantity') }}" } ,
			{ view: "increment" , label: false , text: '<span class="button button-small">+</span>' } ,
			{ attr: "total" , label: "{{ trans('widget::global.subtotal') }}", view: 'currency' } ,
			{ view: "remove" , text: '<span class="button button-small button-assertive">&times;</span>', label: false }
		]
	});
<?php
if(Session::pull('empty_cart', false))
{
?>
	simpleCart.empty();
<?php
}

} // Conflict, missing settings
/*
simpleCart.bind('beforeAdd', function (item) {
	var requestedQuantity = item.get('quantity');
	var existingItem = simpleCart.has(item);
	if (existingItem) {
		requestedQuantity += existingItem.get('quantity');
	}
	if (requestedQuantity > 2) {
		alert("You have reached the maximum quantity of this size.");
		return false;
	}
});

if($simpleCart != 'simpleCart')
{
?>
var {{ $simpleCart }} = simpleCart.copy('{{ $simpleCart }}');
<?php
}
?>

simpleCartInstance.push({{ $simpleCart }});
simpleCartInstance = $.unique(simpleCartInstance);
*/ ?>
</script> 
  </ion-content>
</ion-view>