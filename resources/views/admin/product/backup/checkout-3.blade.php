@extends('layouts.frontend.frontLayout')
@section('title', 'Laravel 5.5 CRUD example')
@section('content')

<div id="site-main" class="site-main">
	<div id="main-content" class="main-content">
		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main"> @include('includes.return_policy_header')
				<div class="external-pages-spacing">
					<div id="title" class="page-title">
						<div class="section-container">
							<div class="content-title-heading">
								<h1 class="text-title-heading">
									Checkout
								</h1>
							</div>
							<div class="breadcrumbs">
								<a href="<?php echo url('/'); ?>">Home</a><span class="delimiter"></span>
								<a href="<?php echo url('/cart'); ?>">Shop</a><span class="delimiter"></span>Shopping
								Cart
							</div>
						</div>
					</div>

					<div class="external-pages-content">
						<div class="section-container p-l-r">
							<ul class="nav nav-tabs steps-tabs" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="step-1-tab">
										<p>Step 1</p>
										<p>Customer Informations</p>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="step-2-tab">
										<p>Step 2</p>
										<p>Choose Shipping</p>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="step-3-tab">
										<p>Step 3</p>
										<p>Submit Order</p>
									</a>
								</li>
							</ul>
							<div class="tab-content text-dark" id="myTabContent">
								<div class="tab-pane fade show active" id="step-1" role="tabpanel"
									aria-labelledby="step-1-tab">
									<div class="checkout-step">
										<div class="shop-checkout">
											<div class="checkout">
												<div class="row">
													<div class="col-xl-8 col-lg-7 col-md-12 col-12">
														<div class="customer-details">
															<div class="billing-fields">
																<h3>Billing details</h3>
																<div class="billing-fields-wrapper">
																	<div class="row">
																		<div class="col-md-6">
																			<p
																				class="form-row form-row-first validate-required">
																				<label>First name <span class="required"
																						title="required">*</span></label>
																				<span class="input-wrapper"><input
																						type="text" class="input-text"
																						name="billing_first_name"
																						id="billing_first_name"
																						value=""></span>
																			</p>
																		</div>
																		<div class="col-md-6">
																			<p
																				class="form-row form-row-last validate-required">
																				<label>Last name <span class="required"
																						title="required">*</span></label>
																				<span class="input-wrapper"><input
																						type="text" class="input-text"
																						name="billing_last_name"
																						id="billing_last_name"
																						value=""></span>
																			</p>
																		</div>
																		<div class="col-sm-4">
																			<p
																				class="form-row form-row-wide validate-required">
																				<label>Country / Region <span
																						class="required"
																						title="required">*</span></label>
																				<span class="input-wrapper">
																					<select name="billing_country"
																						id="billing_country"
																						class="country-select">
																						<option value="">Select a
																							country / region…</option>
																						<?php foreach($data['country'] as $row){ ?>
																						<option
																							value="{{$row->country_id}}">
																							{{$row->name}}
																						</option>
																						<?php } ?>
																					</select>
																				</span>
																			</p>
																		</div>
																		<div class="col-sm-4">
																			<p
																				class="form-row address-field validate-required validate-state form-row-wide">
																				<label>State <span class="required"
																						title="required">*</span></label>
																				<span class="input-wrapper">
																					<select name="billing_state"
																						id="billing_state"
																						class="state-select">
																						<option value="">Select a state
																						</option>
																					</select>
																				</span>
																			</p>
																		</div>
																		<div class="col-sm-4">
																			<p
																				class="form-row address-field validate-required form-row-wide">
																				<label for="billing_city" class="">Town
																					/ City <span class="required"
																						title="required">*</span></label>
																				<span class="input-wrapper">
																					<input type="text"
																						class="input-text"
																						name="billing_city"
																						id="billing_city" value="">
																				</span>
																			</p>
																		</div>
																		<div class="col-sm-6">
																			<p
																				class="form-row address-field form-row-wide">
																				<label>Apartment, suite, unit,
																					etc.&nbsp;<span
																						class="optional">(optional)</span></label>
																				<span class="input-wrapper">
																					<input type="text"
																						class="input-text"
																						name="billing_address_2"
																						id="billing_address_2"
																						placeholder="Apartment, suite, unit, etc. (optional)"
																						value="">
																				</span>
																			</p>
																		</div>
																		<div class="col-sm-6">
																			<p
																				class="form-row address-field validate-required form-row-wide">
																				<label>Street address <span
																						class="required"
																						title="required">*</span></label>
																				<span class="input-wrapper">
																					<input type="text"
																						class="input-text"
																						name="billing_address_1"
																						id="billing_address_1"
																						placeholder="House number and street name"
																						value="">
																				</span>
																			</p>
																		</div>
																		<div class="col-sm-6">
																			<p
																				class="form-row address-field validate-required validate-postcode form-row-wide">
																				<label>Postcode / ZIP <span
																						class="required"
																						title="required">*</span></label>
																				<span class="input-wrapper">
																					<input type="text"
																						class="input-text"
																						name="billing_zipcode"
																						id="billing_zipcode" value="">
																				</span>
																			</p>
																		</div>
																		<div class="col-sm-6">
																			<p
																				class="form-row form-row-wide validate-required validate-phone">
																				<label>Phone <span class="required"
																						title="required">*</span></label>
																				<span class="input-wrapper">
																					<input type="tel" class="input-text"
																						name="billing_phone"
																						id="billing_phone" value="">
																				</span>
																			</p>
																		</div>
																		<div class="col-sm-6">
																			<p class="form-row form-row-wide">
																				<label>Company name <span
																						class="optional">(optional)</span></label>
																				<span class="input-wrapper"><input
																						type="text" class="input-text"
																						name="billing_company"
																						id="billing_company"
																						value=""></span>
																			</p>
																		</div>
																		<div class="col-sm-6">
																			<p
																				class="form-row form-row-wide validate-required validate-email">
																				<label>Email address <span
																						class="required"
																						title="required">*</span></label>
																				<span class="input-wrapper">
																					<input type="email"
																						class="input-text"
																						name="billing_email"
																						id="billing_email" value=""
																						autocomplete="off">
																				</span>
																			</p>
																		</div>
																	</div>
																</div>
															</div>
															<div class="account-fields">
																<p class="form-row form-row-wide">
																	<label class="checkbox">
																		<input class="input-checkbox" type="checkbox"
																			name="createaccount" id="createaccount"
																			value="1">
																		<span>Create an account?</span>
																	</label>
																</p>
																<div class="create-account">
																	<p class="form-row validate-required">
																		<label>Create account password <span
																				class="required"
																				title="required">*</span></label>
																		<span class="input-wrapper password-input">
																			<input type="password" class="input-text"
																				name="account_password"
																				id="account_password" value=""
																				autocomplete="off">
																			<span class="show-password-input"></span>
																		</span>
																	</p>
																	<div class="clear"></div>
																</div>
															</div>
														</div>
														<div class="shipping-fields">
															<p class="form-row form-row-wide ship-to-different-address">
																<label class="checkbox">
																	<input class="input-checkbox" type="checkbox"
																		name="ship_to_different_address"
																		id="ship_to_different_address" value="1">
																	<span>Ship to a different address?</span>
																</label>
															</p>
															<div class="shipping-address">
																<div class="row">
																	<div class="col-sm-6">
																		<p
																			class="form-row form-row-first validate-required">
																			<label>First name <span class="required"
																					title="required">*</span></label>
																			<span class="input-wrapper">
																				<input type="text" class="input-text"
																					name="shipping_first_name" value="">
																			</span>
																		</p>
																	</div>
																	<div class="col-sm-6">
																		<p
																			class="form-row form-row-last validate-required">
																			<label>Last name <span class="required"
																					title="required">*</span></label>
																			<span class="input-wrapper">
																				<input type="text" class="input-text"
																					name="shipping_last_name" value="">
																			</span>
																		</p>
																	</div>
																	<div class="col-sm-4">
																		<p
																			class="form-row form-row-wide address-field validate-required">
																			<label for="shipping_country"
																				class="">Country / Region <span
																					class="required"
																					title="required">*</span></label>
																			<span class="input-wrapper">
																				<select name="shipping_country"
																					class="state-select">
																					<option value="">Select a country /
																						region…</option>
																					<?php foreach($data['country'] as $row){ ?>
																					<option
																						value="{{$row->country_id}}">
																						{{$row->name}}
																					</option>
																					<?php } ?>
																				</select>
																			</span>
																		</p>
																	</div>
																	<div class="col-sm-4">
																		<p
																			class="form-row address-field validate-required validate-state form-row-wide">
																			<label for="shipping_state" class="">State /
																				County <span class="required"
																					title="required">*</span></label>
																			<span class="input-wrapper">
																				<select name="shipping_state"
																					class="state-select">
																					<option value="">Select a state
																					</option>

																				</select>
																			</span>
																		</p>
																	</div>
																	<div class="col-sm-4">
																		<p
																			class="form-row address-field validate-required form-row-wide">
																			<label>Town / City <span class="required"
																					title="required">*</span></label>
																			<span class="input-wrapper"><input
																					type="text" class="input-text"
																					name="shipping_city"
																					value=""></span>
																		</p>
																	</div>
																	<div class="col-sm-6">
																		<p
																			class="form-row address-field validate-required form-row-wide">
																			<label>Street address <span class="required"
																					title="required">*</span></label>
																			<span class="input-wrapper">
																				<input type="text" class="input-text"
																					name="shipping_address_1"
																					placeholder="House number and street name"
																					value="">
																			</span>
																		</p>
																	</div>
																	<div class="col-sm-6">
																		<p class="form-row address-field form-row-wide">
																			<label>Apartment, suite, unit, etc. <span
																					class="optional">(optional)</span></label>
																			<span class="input-wrapper">
																				<input type="text" class="input-text"
																					name="shipping_address_2"
																					placeholder="Apartment, suite, unit, etc. (optional)"
																					value="">
																			</span>
																		</p>
																	</div>
																</div>

																<p
																	class="form-row address-field validate-required validate-postcode form-row-wide">
																	<label>Postcode / ZIP <span class="required"
																			title="required">*</span></label>
																	<span class="input-wrapper">
																		<input type="text" class="input-text"
																			name="shipping_postcode" value="">
																	</span>
																</p>

																<p class="form-row form-row-wide">
																	<label>Company name <span
																			class="optional">(optional)</span></label>
																	<span class="input-wrapper">
																		<input type="text" class="input-text"
																			name="shipping_company" value="">
																	</span>
																</p>

															</div>
														</div>
														<div class="additional-fields">
															<p class="form-row notes">
																<label>Order notes <span
																		class="optional">(optional)</span></label>
																<span class="input-wrapper">
																	<textarea name="order_comments" class="input-text"
																		placeholder="Notes about your order, e.g. special notes for delivery."
																		rows="2" cols="5"></textarea>
																</span>
															</p>
														</div>
													</div>
													<div class="col-xl-4 col-lg-5 col-md-12 col-12">
														<div class="checkout-review-order">
															<div class="checkout-review-order-table">
																<div class="review-order-title">Product</div>
																<div class="cart-items">
																	@foreach ($data['cart_data'] as $key => $value)
																	@php

																	@endphp
																	<div class="cart-item">
																		<div class="info-product">
																			<div class="product-thumbnail">
																				<div class="full frameWrap relative portraitView"
																					style="width: 70px;">
																					<div class="mainFramePort"><img
																							src="https://demo.fireplacelove.com/public/front2/images/portrait/frame/por1.png"
																							alt="" width="104"
																							height="138"
																							class="img-responsive">
																					</div>
																					<div class="cerOuterPort"><img
																							src="https://demo.fireplacelove.com/public/uploads/passporto/portrait/1522904796.png"
																							alt="" width="104"
																							height="240"
																							class="img-responsive">
																					</div>
																					<div class="frameBitPort"><img
																							src="https://demo.fireplacelove.com/public/uploads/frame/portrait/1522923608.png"
																							alt="" width="104"
																							height="239"
																							class="img-responsive">
																						<div class="clear"></div>
																					</div>
																					<div class="certificatePort"></div>
																					<div class="frameBdrPort"></div>
																					<div class="cerTopPort"><img
																							src="https://demo.fireplacelove.com/public/uploads/seal/portrait/1522927073.png"
																							alt="" width="104"
																							height="269"
																							class="img-responsive">
																					</div>
																					<div class="cerBottomPort"><img
																							src="https://demo.fireplacelove.com/public/front2/images/portrait/cerName/1.png"
																							alt="" width="104"
																							height="250"
																							class="img-responsive">
																					</div>
																				</div>
																			</div>
																			<div class="product-name">
																				Chair Oak Matt Lacquered
																				<strong class="product-quantity">QTY :
																					{{$value['qty']}}</strong>
																			</div>
																		</div>
																		<div class="product-total">
																			<span>$<?php echo number_format($value['price'], 2, '.', '');?></span>
																		</div>
																	</div>
																	@endforeach

																</div>
																<div class="cart-subtotal">
																	<h2>Subtotal</h2>
																	<div class="subtotal-price">
																		<span>${{$data['sub_total']}}</span>
																	</div>
																</div>

																<div class="order-total">
																	<h2>Total</h2>
																	<div class="total-price">
																		<strong>
																			<span>${{$data['sub_total']}}</span>
																		</strong>
																	</div>
																</div>
															</div>
															<div id="payment" class="checkout-payment">
																<div class="form-row place-order">
																	<div class="terms-and-conditions-wrapper">
																		<div class="privacy-policy-text"></div>
																	</div>
																	<button type="button"
																		class="button alt continue_btn" step="1"
																		nextStep="2">Continue</button>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="step-2" role="tabpanel" aria-labelledby="step-2-tab">
									<div class="checkout-step">
										<div class="row">
											<div class="col-lg-8 mb-4 mb-lg-0">
												<div class="full p-h-20">
													<div class="row">
														<div class="col-md-6 col-sm-6 col-xs-12">
															<div class="full lightbg2 radius3 p20 info_r">
																<h6>Billing Information</h6>

																<div class="rev_billing_info">

																</div>
															</div>
														</div>
														<div class="col-md-6 col-sm-6 col-xs-12">
															<div class="full lightbg2 radius3 p20 info_r">
																<h6>Shipping Information</h6>
																<div class="rev_shipping_info">

																</div>
															</div>
														</div>

													</div>
												</div>
												<div class="fieldBox full relative m-b-10">

													<p style="font-size: 11px;">** Our international orders, your
														packages may be subject to the customs fees and import duties of
														the country to which your order ships. These charges are always
														the receipient's responsibility and are not included in shipping
														charges. Contact your local customs office for details </p>
												</div>

											</div>
											<div class="col-lg-4">
												<div class="checkout-step-inner">
													<div class="delivery-header">
														<div class="d-flex justify-content-between">
															<p>Total({{$data['total_item']}} item)</p>
															<p>$ {{$data['sub_total']}}</p>
														</div>
														<div class="d-flex justify-content-between">
															<div class="">
																<p class="mb-0">Delivery </p>
																<p class="border-bottom">(To United Statees - 12202)</p>
															</div>

															<a href="#" class="text-success">
																<p>Select Shipping Below</p>
															</a>
														</div>
													</div>
													<div class="delivery-body border-top pt-3">
														<div class="d-flex justify-content-between">
															<p>Total({{$data['total_item']}} item)</p>
															<p>$ {{$data['sub_total']}}</p>
														</div>
														<div class="select-shipping" id="shipping_option">
															{{-- <div class="custom-control custom-radio mb-3">
																<input type="radio" id="customRadio1" name="customRadio"
																	class="custom-control-input">
																<label class="custom-control-label"
																	for="customRadio1">Priority Mail Express 1-Day ----
																	$ 77</label>
															</div> --}}
														</div>
													</div>
													<div class="deliver-footer mt-4">
														<button type="submit" class="button btn-dark go_back_btn"
															step="2" prevStep="1"> Go back</button>
														<button type="button" class="button alt continue_btn" step="2"
															nextStep="3">Continue</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="step-3" role="tabpanel" aria-labelledby="step-3-tab">
									<div class="checkout-step">
										<div class="row">
											<div class="col-lg-8 mb-4 mb-lg-0">
												{{-- <div class="d-flex justify-content-between align-items-center mb-4">
													<p class="mb-0 d-flex justify-content-between align-items-center">
														<img src="https://demo.fireplacelove.com/public/front2/images/portrait/frame/por1.png"
															alt="" class="img-fluid mr-3" width="40">
														<span>GreatNorthCarpentry</span></p>

													<button class="btn btn-secondary">Contact Shop</button>
												</div> --}}
												@foreach ($data['cart_data'] as $key => $value)
												@php
												$product_price = $data['base_price'];
												$dimentions_arr=explode('X',$value['dimentions']);
												@endphp
												<div class="row product-checkout-details mx-0">
													<div class="col-lg-3 mb-4 mb-lg-0">
														<img src="https://demo.fireplacelove.com/public/front2/images/portrait/frame/por1.png"
															alt="" class="img-fluid mr-3 bg-secondary border">

													</div>
													<div class="col-lg-6 mb-4 mb-lg-0">
														<div class="checkout-product-details bg-light p-3 mb-4">
															<h4>Baby Proof / Draft Stopper Fireplace Cover l</h4>
															<ul class="list-unstyled">
																<li>
																	<h3>Passporto:
																		<?php echo $value['passporto']['title'];?>
																	</h3>
																</li>
																<li>Frame:
																	<?php echo $value['frame']['title'];?></li>
																<li>Seals:
																	<?php echo $value['seals']['title'];?></li>
																<li>Width: <?php echo $dimentions_arr[0];?>
																	Inches</li>
																<li>Height: <?php echo $dimentions_arr[1];?>
																	Inches</li>
																<li>Depth: <?php echo $dimentions_arr[2];?>
																	Inches</li>
															</ul>

															{{-- <div class="text-right"><button
																	class="btn btn-secondary edit-selectins">Edit
																	Selections</button></div> --}}
														</div>
													</div>
													<div class="col-lg-3 text-right">
														<h4>$<?php echo number_format($product_price, 2, '.', '');?>
														</h4>
														{{-- <h5 class="text-danger">Only I available and it's in 8
															people's baskets</h5> --}}
													</div>
												</div>
												@endforeach

												<div class="row mt-5 mx-0">
													<div class="col-md-6">
														<div class="form-group">
															<label for="greatnorthcarpentry">+ Add a note to
																GreatNorthCarpentry</label>
															<textarea id="greatnorthcarpentry" class="form-control"
																name=""
																rows="2">+ Add a note to GreatNorthCarpentry (Optional)</textarea>
														</div>
													</div>
													<div class="col-md-6 text-right">
														<h4>Delivery: <span class="text-success">Prioriy Mail Express
																1-Day</span></h4>
														<p>Ready to dispatch in 1-3 weeks <br>
															from United States</p>
													</div>
												</div>

											</div>
											<div class="col-lg-4">
												<div class="checkout-step-inner">
													<div class="row mx-0">
														<div class="col-md-6">
															<div class="custom-control custom-radio">
																<input type="radio" id="customRadio1" name="customRadio"
																	class="custom-control-input">
																<label class="custom-control-label" for="customRadio1">
																	<p class="mb-0">Card</p>
																</label>
															</div>
														</div>
														<div class="col-md-auto ml-auto"><img
																src="https://cdn.pixabay.com/photo/2022/03/16/18/29/bank-7073043_640.png"
																alt="" class="img-fluid" width="35"></div>
													</div>
													<div class="row mt-4">
														<div class="form-group col-md-12">
															<label for="my-input">Name on Card <span class="required"
																	title="required">*</span>
																<p class="mb-0">Make sure to enter the full name that's
																	on your card.</p>
															</label>
															<input id="my-input" class="form-control" type="text"
																name="">
														</div>
														<div class="input-group col-md-12 mb-3">
															<div class="input-group-prepend">
																<span class="input-group-text"><i
																		class="fa fa-credit-card"></i></span>
															</div>
															<input class="form-control" type="text" name=""
																placeholder="Card Number" aria-label="Card Number">
															<div class="input-group-append">
																<span class="input-group-text"><i
																		class="fa fa-lock"></i></span>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="card-expire">Expiration Date <span
																		class="required"
																		title="required">*</span></label>
																<select id="card-expire" class="form-control" name="">
																	<option>12</option>
																</select>
															</div>
															<div class="form-group">
																<select id="card-year" class="form-control" name="">
																	<option>2028</option>
																</select>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group position-relative">
																<label for="card-secuity">Security Code <span
																		class="required"
																		title="required">*</span></label>
																<input id="card-secuity" class="form-control"
																	type="text" name="" value="546">
																<div class="security-lock"><i class="fa fa-lock"></i>
																</div>
															</div>
														</div>
														<div class="col-12">
															<div class="custom-control custom-checkbox">
																<input type="checkbox" class="custom-control-input"
																	id="customCheck1">
																<label class="custom-control-label"
																	for="customCheck1"><span>My billing address is the
																		same as my delivery address:</span>
																	<h6>
																		Saptarsha Saha, 72/A Basunagar , Madhyamgram,
																		Gate No 1 , Near Arati Medico, KOLKATA
																		700129, West Bengal, India, 980-407-0412
																	</h6>
																</label>
															</div>
														</div>

														<div class="col-12 mt-5">
															<button type="submit"
																class="btn btn-block py-3 btn-dark mb-3">Review and
																Submit</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div><!-- #content -->
				</div>

			</div>
			<!-- #content -->
		</div>
		<!-- #primary -->
	</div>
	@push('stylesheet')
	<style>
		.error {
			color: #a94442;
		}
	</style>

	@endpush
	@push('scripts')
	<script>
		function billing_shipping_html() {
			var billing_first_name = $.trim($('input[name="billing_first_name"]').val());
			var billing_last_name = $.trim($('input[name="billing_last_name"]').val());
			var billing_company = $.trim($('input[name="billing_company"]').val());
			var billing_address_1 = $.trim($('input[name="billing_address_1"]').val());
			var billing_address_2 = $.trim($('input[name="billing_address_2"]').val());
			var billing_city = $.trim($('input[name="billing_city"]').val());
			var billing_state = $.trim($('select[name="billing_state"] option:selected').text());
			var billing_zipcode = $.trim($('input[name="billing_zipcode"]').val());
			var billing_country = $.trim($('select[name="billing_country"] option:selected').text());
			var billing_email = $.trim($('input[name="billing_email"]').val());
			var billing_phone = $.trim($('input[name="billing_phone"]').val());
			var shipping_email = billing_email;
			if ($('#ship_to_different_address').is(":checked")) {
				var shipping_first_name = $.trim($('input[name="shipping_first_name"]').val());
				var shipping_last_name = $.trim($('input[name="shipping_last_name"]').val());
				var shipping_country = $.trim($('select[name="shipping_country"] option:selected').text());
				var shipping_state = $.trim($('select[name="shipping_state"] option:selected').text());
				var shipping_city = $.trim($('input[name="shipping_city"]').val());
				var shipping_address_1 = $.trim($('input[name="shipping_address_1"]').val());
				var shipping_address_2 = $.trim($('input[name="shipping_address_2"]').val());
				var shipping_zipcode = $.trim($('input[name="shipping_postcode"]').val());
				var shipping_company = $.trim($('input[name="shipping_company"]').val());
			} else {
				shipping_first_name = billing_first_name;
				shipping_last_name = billing_last_name;
				shipping_country = billing_country;
				shipping_state = billing_state;
				shipping_city = billing_city;
				shipping_address_1 = billing_address_1;
				shipping_address_2 = billing_address_2;
				shipping_zipcode = billing_zipcode;
				shipping_company = billing_company;
			}
			var billing_html = '';
			var shipping_html = '';
			billing_html += '<p>' + billing_first_name + ' ' + billing_last_name + '</p>\
			<p>' + billing_company + '</p><p>' + billing_address_1 + '</p><p>' + billing_email + '</p><p>' + billing_phone +
				'</p><p>' + billing_city + '</p><p>' + billing_state + ', ' + billing_zipcode + '</p><p>' + billing_country +
				'</p>';
			shipping_html += '<p>' + shipping_first_name + ' ' + shipping_last_name + '</p>\
			<p>' + shipping_company + '</p><p>' + shipping_address_1 + '</p><p>' + shipping_email + '</p><p>' + shipping_city +
				'</p><p>' + shipping_state + ', ' + shipping_zipcode + '</p><p>' + shipping_country + '</p>';
			
			$('.rev_shipping_info').html(shipping_html);
			$('.rev_billing_info').html(billing_html);

			
			var data = {
				'shipping_address_1': shipping_address_1,
				'shipping_address_2': shipping_address_2,
				'shipping_city': shipping_city,
				'shipping_state': shipping_state,
				'shipping_zipcode': shipping_zipcode,
				'shipping_country': shipping_country,
			};

			swal.fire({
				showCancelButton: false,
				showConfirmButton: false,
				allowOutsideClick: false,
				html: '<h4>Loading...</h4>',
				onRender: function() {
					$('.swal2-content').prepend(sweet_loader);
				}
			});
			$.ajax({
				method: "POST",
				url: "<?php echo url('ajax_calculate_shipping'); ?>",
				dataType : 'json',
				data: { _token: prop.csrf_token, data: data},
				success: function(data) {
					if(data.success==1){
						swal.close();

						var html = '';
						if(data.shippingMailService.length>0){
							for(var i = 0; i < data.shippingMailService.length; i++) {
								html += '<div class="custom-control custom-radio mb-3"><input type="radio" value="'+data.shippingMailService[i]['rate']+'" id="shippingMailService_'+i+'" name="shippingMailService" class="custom-control-input"><label class="custom-control-label" for="shippingMailService_'+i+'">'+data.shippingMailService[i]['mailService']+' $ '+data.shippingMailService[i]['rate']+'</label></div>';
							}
						}
						$('#shipping_option').html(html);
						$('#step-1').removeClass('show active');
						$('#step-2-tab').addClass('active');
						$('#step-2').addClass('show active');
						
					}else{
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: data.errorMsg,
						});
						return false;
					}
				}
			});

			
		}

		function validateStep1() {
			var billing_first_name = $.trim($('input[name="billing_first_name"]').val());
			var billing_last_name = $.trim($('input[name="billing_last_name"]').val());
			var billing_company = $.trim($('input[name="billing_company"]').val());
			var billing_address_1 = $.trim($('input[name="billing_address_1"]').val());
			var billing_city = $.trim($('input[name="billing_city"]').val());
			var billing_state = $.trim($('select[name="billing_state"]').val());
			var billing_zipcode = $.trim($('input[name="billing_zipcode"]').val());
			var billing_country = $.trim($('select[name="billing_country"]').val());
			var billing_email = $.trim($('input[name="billing_email"]').val());
			var billing_phone = $.trim($('input[name="billing_phone"]').val());
			var account_password = $.trim($('input[name="account_password"]').val());
			var shipping_first_name = $.trim($('input[name="shipping_first_name"]').val());
			var shipping_last_name = $.trim($('input[name="shipping_last_name"]').val());
			var shipping_country = $.trim($('select[name="shipping_country"]').val());
			var shipping_state = $.trim($('select[name="shipping_state"]').val());
			var shipping_city = $.trim($('input[name="shipping_city"]').val());
			var shipping_address_1 = $.trim($('input[name="shipping_address_1"]').val());
			var shipping_postcode = $.trim($('input[name="shipping_postcode"]').val());
			var error = 0;
			var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
			$(".field_error, .ajax_success").remove();
			if (billing_first_name == "") {
				$("input[name='billing_first_name']").parent().append(
					"<span class='clearfix field_error error'>Enter first name</span>");
				error = 1;
			}
			if(billing_last_name == "") {
			  $("input[name='billing_last_name']").parent().append("<span class='clearfix field_error error'>Enter last name</span>");
			  error = 1;
			}
			if(billing_address_1 == "") {
			  $("input[name='billing_address_1']").parent().append("<span class='clearfix field_error error'>Enter address</span>");
			  error = 1;
			}
			if(billing_city == "") {
			  $("input[name='billing_city']").parent().append("<span class='clearfix field_error error'>Enter city</span>");
			  error = 1;
			}
			if(billing_state == "") {
			  $("select[name='billing_state']").parent().append("<span class='clearfix field_error error'>Select state</span>");
			  error = 1;
			}
			if(billing_zipcode == "") {
			  $("input[name='billing_zipcode']").parent().append("<span class='clearfix field_error error'>Enter zipcode</span>");
			  error = 1;
			}
			if(billing_country == "") {
			  $("select[name='billing_country']").parent().append("<span class='clearfix field_error error'>Select country</span>");
			  error = 1;
			}
			if(!email_pattern.test(billing_email)) {
			  $("input[name='billing_email']").parent().append("<span class='clearfix field_error error'>Enter valid email address</span>");
			  error = 1;
			}
			if(billing_phone == "" || isNaN(billing_phone)) {
			  $("input[name='billing_phone']").parent().append("<span class='clearfix field_error error'>Enter phone number</span>");
			  error = 1;
			}
			if ($('#createaccount').is(":checked")) {
				if (account_password == "") {
					$("input[name='account_password']").parent().append(
						"<span class='clearfix field_error error'>Enter account password</span>");
					error = 1;
				}
			}
			if ($('#ship_to_different_address').is(":checked")) {
				if (shipping_first_name == "") {
					$("input[name='shipping_first_name']").parent().append(
						"<span class='clearfix field_error error'>Enter first name</span>");
					error = 1;
				}
				if (shipping_last_name == "") {
					$("input[name='shipping_last_name']").parent().append(
						"<span class='clearfix field_error error'>Enter last name</span>");
					error = 1;
				}
				if (shipping_address_1 == "") {
					$("input[name='shipping_address_1']").parent().append(
						"<span class='clearfix field_error error'>Enter address</span>");
					error = 1;
				}
				if (shipping_city == "") {
					$("input[name='shipping_city']").parent().append(
						"<span class='clearfix field_error error'>Enter city</span>");
					error = 1;
				}
				if (shipping_state == "") {
					$("select[name='shipping_state']").parent().append(
						"<span class='clearfix field_error error'>Select state</span>");
					error = 1;
				}
				if (shipping_postcode == "") {
					$("input[name='shipping_postcode']").parent().append(
						"<span class='clearfix field_error error'>Enter zipcode</span>");
					error = 1;
				}
				if (shipping_country == "") {
					$("select[name='shipping_country']").parent().append(
						"<span class='clearfix field_error error'>Select country</span>");
					error = 1;
				}
			}
			if (error == 1)
				return false;
			else
				return true;
		}
		$('.go_back_btn').click(function() {
			var step = $(this).attr('step');
			var prevStep = $(this).attr('prevStep');
			$('#step-' + step).removeClass('show active');
			$('#step-' + step + '-tab').removeClass('active');
			$('#step-' + prevStep).addClass('show active');
		});
		$('.continue_btn').click(function() {
			var step = $(this).attr('step');
			var nextStep = $(this).attr('nextStep');
			if (window["validateStep" + step]()) {
				if (step == 1) {
					billing_shipping_html();
				} else if (step == 2) {

				}
				
			}
			return false;
		});
		$('select[name="billing_country"]').change(function() {
			var country_id = $(this).val();
			if (country_id != '') {
				var states = get_states(country_id, 'select[name="billing_state"]');
			} else {
				$('select[name="billing_state"]').html('<option value="">Select State</option>');
			}
		});
		$('select[name="shipping_country"]').change(function() {
			var country_id = $(this).val();
			if (country_id != '') {
				var states = get_states(country_id, 'select[name="shipping_state"]');
			} else {
				$('select[name="shipping_state"]').html('<option value="">Select State</option>');
			}
		});

		function get_states(country_id, target) {
			$.ajax({
				method: "GET",
				url: "<?php echo url('ajax_get_states'); ?>",
				dataType: 'json',
				data: {
					country_id: country_id
				},
				success: function(data) {
					state_html(data, target, true);
				}
			});
		}

		function state_html(data, target, show) {
			var html = '<option value="">Select State</option>';
			for (var i = 0; i < data.length; i++) {
				html += '<option value="' + data[i].zone_id + '">' + data[i].name + '</option>';
			}
			if (show && target != '')
				$(target).html(html);
			else
				return html;
		}
	</script>

	@endpush
	<div class="clear"></div>
	@endsection