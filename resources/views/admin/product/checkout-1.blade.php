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
								<div class="tab-pane fade show active" id="step-1" role="tabpanel" aria-labelledby="step-1-tab">
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
																						<option value="{{$row->country_id}}">
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
																				<label>State <span
																						class="required"
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
																						name="billing_postcode"
																						id="billing_postcode" value="">
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
																		name="ship_to_different_address" value="1">
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
																							<option value="{{$row->country_id}}">
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
																					<option value="">Select a state</option>
																					
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
																					2</strong>
																			</div>
																		</div>
																		<div class="product-total">
																			<span>$300.00</span>
																		</div>
																	</div>
																	
																</div>
																<div class="cart-subtotal">
																	<h2>Subtotal</h2>
																	<div class="subtotal-price">
																		<span>$480.00</span>
																	</div>
																</div>
																<div class="shipping-totals shipping">
																	<div class="flex-1">
																		<h2>Shipping</h2>
																		<button class="btn btn-primary mt-2"
																			type="button" data-toggle="collapse"
																			data-target="#collapseExample"
																			aria-expanded="false"
																			aria-controls="collapseExample">
																			Calculate
																		</button>
																	</div>

																	<div data-title="Shipping" class="flex-1">
																		<div class="collapse show" id="collapseExample">
																			<ul class="p-3 custom-radio mb-0">
																				<li><input type="radio"
																						name="shipping_method"
																						data-index="0"
																						value="free_shipping"
																						class="shipping_method"
																						checked="checked"><label>Free
																						shipping</label></li>
																				<li><input type="radio"
																						name="shipping_method"
																						data-index="0" value="flat_rate"
																						class="shipping_method"><label>Flat
																						rate</label></li>
																			</ul>
																		</div>

																	</div>
																</div>

																<div class="order-total">
																	<h2>Total</h2>
																	<div class="total-price">
																		<strong>
																			<span>$480.00</span>
																		</strong>
																	</div>
																</div>
															</div>
															<div id="payment" class="checkout-payment">
																<ul class="payment-methods methods custom-radio">
																	<li class="payment-method">
																		<input type="radio" class="input-radio"
																			name="payment_method" value="bacs"
																			checked="checked">
																		<label for="payment_method_bacs">Direct bank
																			transfer</label>
																		<div class="payment-box">
																			<p>Make your payment directly into our bank
																				account. Please use your Order ID as the
																				payment reference. Your order will not
																				be shipped until the funds have cleared
																				in our account.</p>
																		</div>
																	</li>
																	<li class="payment-method">
																		<input type="radio" class="input-radio"
																			name="payment_method" value="cheque">
																		<label>Check payments</label>
																		<div class="payment-box">
																			<p>Please send a check to Store Name, Store
																				Street, Store Town, Store State /
																				County, Store Postcode.</p>
																		</div>
																	</li>
																	<li class="payment-method">
																		<input type="radio" class="input-radio"
																			name="payment_method" value="cod">
																		<label>Cash on delivery</label>
																		<div class="payment-box">
																			<p>Pay with cash upon delivery.</p>
																		</div>
																	</li>
																	<li class="payment-method">
																		<input type="radio" class="input-radio"
																			name="payment_method" value="paypal">
																		<label>PayPal</label>
																		<div class="payment-box">
																			<p>Pay via PayPal; you can pay with your
																				credit card if you don’t have a PayPal
																				account.</p>
																		</div>
																	</li>
																</ul>
																<div class="form-row place-order">
																	<div class="terms-and-conditions-wrapper">
																		<div class="privacy-policy-text"></div>
																	</div>
																	<button type="submit" class="button alt"
																		name="checkout_place_order"
																		value="Place order">Place order</button>
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
												<h1>Choose Delivery Address</h1>
												<div class="row my-4">
													<div class="col-md-6 mb-4 mb-md-0">
														<div class="custom-control custom-radio">
															<input type="radio" id="customRadio1" name="customRadio"
																class="custom-control-input">
															<label class="custom-control-label" for="customRadio1">
																<div class="badge badge-secondary">Default</div>
																<p class="mb-0">Saptarsha Saha</p>
																<p class="mb-0">72/A Basunagar , Madhyamgram</p>
																<p class="mb-0">Gate No 1 , Near Arati Medico</p>
																<p class="mb-0">KOLKATA 700129</p>
																<p class="mb-0">West Bengal</p>
																<p class="mb-0">India</p>
																<p class="mb-0">9804070412</p>
															</label>
														</div>
													</div>
													<div class="col-md-6">
														<button type="submit"
															class="btn btn-block py-3 btn-dark mb-3">Dispatch
															Here</button>
														<div class="d-flex action_btns">
															<button type="submit"
																class="btn bg-transparent flex-1">Edit</button>
															<button type="submit"
																class="btn bg-transparent flex-1">Delete</button>
														</div>
													</div>
												</div>
												<p>
													By choosing -Dispatch here," you agree to Etsy's and consent to
													receiving order
													confirmations from Etsy and its service partners via SMS or WhatsApp
													using your phone number.
													Message and data rates may apply.
												</p>

												<!-- Button trigger modal -->
												<button type="button" class="btn py-2 btn-secondary" data-toggle="modal"
													data-target="#add_address">
													Add a New Address
												</button>

												<!-- Modal -->
												<div class="modal fade" id="add_address" tabindex="-1"
													aria-labelledby="add_addressLabel" aria-hidden="true">
													<div class="modal-dialog modal-dialog-centered modal-lg">
														<div class="modal-content">
															<div
																class="modal-header align-items-center d-lg-flex d-block">
																<h5 class="modal-title" id="exampleModalLabel">Enter an
																	Address</h5>
																<button type="button" class="close" data-dismiss="modal"
																	aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">

																<div class="row">
																	<div class="form-group col-md-6">
																		<label for="add-full-name">Full Name <span
																				class="required"
																				title="required">*</span></label>
																		<input id="add-full-name" class="form-control"
																			type="text" name="">
																	</div>
																	<div class="form-group col-md-6">
																		<label for="add-country">Country <span
																				class="required"
																				title="required">*</span></label>
																		<span class="input-wrapper">
																			<select name="add_country"
																				id="add-country"
																				class="country-select custom-select">
																				<option value="">India</option>
																				<option value="">Afghanistan
																				</option>
																				<option value="">Åland Islands
																				</option>
																				<option value="">Albania</option>
																				<option value="">Algeria</option>
																				<option value="">American Samoa
																				</option>
																				<option value="">Andorra</option>
																			</select>
																		</span>
																	</div>
																	<div class="form-group col-md-12">
																		<label for="add-street-address">Street
																			Address <span class="required"
																				title="required">*</span></label>
																		<textarea id="add-street-address"
																			class="form-control" name=""
																			rows="2"></textarea>
																	</div>
																	<div class="form-group col-md-6">
																		<label for="add-landmark">Apt / Suite /
																			Landmark / Other (optional)</label>
																		<input id="add-landmark" class="form-control"
																			type="landmark" name="">
																	</div>
																	<div class="form-group col-md-6">
																		<label for="add-city">City <span
																				class="required"
																				title="required">*</span></label>
																		<input id="add-city" class="form-control"
																			type="text" name="">
																	</div>
																	<div class="form-group col-md-6">
																		<label for="add-pincode">Pincode <span
																				class="required"
																				title="required">*</span></label>
																		<input id="add-pincode" class="form-control"
																			type="text" name="">
																	</div>
																	<div class="form-group col-md-6">
																		<label for="add-state">State <span
																				class="required"
																				title="required">*</span></label>
																		<select id="add-state"
																			class="form-control custom-select" name="">
																			<option>Select State</option>
																		</select>
																	</div>
																	<div class="form-group col-md-12">
																		<label for="add-phone-no">Mobile phone
																			number (to receive order updates) <span
																				class="required"
																				title="required">*</span></label>
																		<div class="input-group">
																			<div class="input-group-prepend">
																				<span class="input-group-text"
																					id="mobile-no">+91</span>
																			</div>
																			<input class="form-control" type="text"
																				name="" placeholder="Recipient's text"
																				aria-label="Recipient's text"
																				aria-describedby="mobile-no">
																		</div>
																	</div>
																	<div class="col-md-12">
																		<div
																			class="custom-control custom-checkbox custom-control-inline">
																			<input id="set-as-default"
																				class="custom-control-input"
																				type="checkbox" name="" value="true">
																			<label for="set-as-default"
																				class="custom-control-label">Set as
																				Default</label>
																		</div>
																	</div>
																	<div
																		class="col-md-12 d-flex justify-content-between mt-4">
																		<button type="submit"
																			class="btn btn-secondary">Cancel</button>
																		<button type="submit"
																			class="btn btn-dark">Save</button>
																	</div>
																</div>

															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-lg-4">
												<div class="checkout-step-inner">
													<div class="delivery-header">
														<div class="d-flex justify-content-between">
															<p>Item(s) Total</p>
															<p>$ 325</p>
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
															<p>Total(1 item)</p>
															<p>$ 325</p>
														</div>
														<div class="select-shipping">
															<div class="custom-control custom-radio mb-3">
																<input type="radio" id="customRadio1" name="customRadio"
																	class="custom-control-input">
																<label class="custom-control-label"
																	for="customRadio1">Priority Mail Express 1-Day ----
																	$ 77</label>
															</div>
															<div class="custom-control custom-radio mb-3">
																<input type="radio" id="customRadio2" name="customRadio"
																	class="custom-control-input">
																<label class="custom-control-label"
																	for="customRadio2">Priority Mail Express 1-Day ----
																	$ 77</label>
															</div>
															<div class="custom-control custom-radio mb-3">
																<input type="radio" id="customRadio3" name="customRadio"
																	class="custom-control-input">
																<label class="custom-control-label"
																	for="customRadio3">Priority Mail Express 1-Day ----
																	$ 77</label>
															</div>
															<div class="custom-control custom-radio mb-3">
																<input type="radio" id="customRadio4" name="customRadio"
																	class="custom-control-input">
																<label class="custom-control-label"
																	for="customRadio4">Priority Mail Express 1-Day ----
																	$ 77</label>
															</div>
															<div class="custom-control custom-radio mb-3">
																<input type="radio" id="customRadio5" name="customRadio"
																	class="custom-control-input">
																<label class="custom-control-label"
																	for="customRadio5">Priority Mail Express 1-Day ----
																	$ 77</label>
															</div>
															<div class="custom-control custom-radio">
																<input type="radio" id="customRadio6" name="customRadio"
																	class="custom-control-input">
																<label class="custom-control-label"
																	for="customRadio6">Priority Mail Express 1-Day ----
																	$ 77</label>
															</div>
															<div class="custom-control custom-radio mb-3">
																<input type="radio" id="customRadio7" name="customRadio"
																	class="custom-control-input">
																<label class="custom-control-label"
																	for="customRadio7">Priority Mail Express 1-Day ----
																	$ 77</label>
															</div>
														</div>
													</div>
													<div class="deliver-footer mt-4">
														<button type="submit"
															class="btn btn-block py-2 btn-dark">Proceed to
															Checkout</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="step-3" role="tabpanel"
									aria-labelledby="step-3-tab">
									<div class="checkout-step">
										<div class="row">
											<div class="col-lg-8 mb-4 mb-lg-0">
												<div class="d-flex justify-content-between align-items-center mb-4">
													<p class="mb-0 d-flex justify-content-between align-items-center">
														<img src="https://demo.fireplacelove.com/public/front2/images/portrait/frame/por1.png"
															alt="" class="img-fluid mr-3" width="40">
														<span>GreatNorthCarpentry</span></p>

													<button class="btn btn-secondary">Contact Shop</button>
												</div>
												<div class="row product-checkout-details mx-0">
													<div class="col-lg-3 mb-4 mb-lg-0">
														<img src="https://demo.fireplacelove.com/public/front2/images/portrait/frame/por1.png"
															alt="" class="img-fluid mr-3 bg-secondary border">

														<div class="d-flex justify-content-between mt-2">
															<button class="btn btn-danger">X Remove</button>
															<button class="btn btn-info">Save for Later</button>
														</div>
													</div>
													<div class="col-lg-6 mb-4 mb-lg-0">
														<div class="checkout-product-details bg-light p-3 mb-4">
															<h4>Baby Proof / Draft Stopper Fireplace Cover l</h4>
															<ul class="list-unstyled">
																<li>Width - Inches (see "how to order" section):
																	<strong>
																		25 - 27
																		inches
																	</strong></li>
																<li>Height - Inches (see 'how to order" section):
																	<strong>
																		20 - 25
																		inches
																	</strong></li>
																<li>personalisation: test</li>
															</ul>

															<div class="text-right"><button
																	class="btn btn-secondary edit-selectins">Edit
																	Selections</button></div>
														</div>
													</div>
													<div class="col-lg-3 text-right">
														<h4>$ 325</h4>
														<h5 class="text-danger">Only I available and it's in 8
															people's baskets</h5>
													</div>
												</div>
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
												<div class="row mx-0">
													<div class="col-12">
														<div class="custom-control custom-radio">
															<input type="radio" id="customRadio1" name="customRadio"
																class="custom-control-input">
															<label class="custom-control-label" for="customRadio1">
																<div class="badge badge-secondary">Default</div>
																<p class="mb-0">Saptarsha Saha</p>
																<p class="mb-0">72/A Basunagar , Madhyamgram</p>
																<p class="mb-0">Gate No 1 , Near Arati Medico</p>
																<p class="mb-0">KOLKATA 700129</p>
																<p class="mb-0">West Bengal</p>
																<p class="mb-0">India</p>
																<p class="mb-0">9804070412</p>
															</label>
														</div>
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

	@endpush
	@push('scripts')
	<script>
	$('select[name="billing_country"]').change(function(){
		var country_id = $(this).val();
		alert(country_id);
		if(country_id != '') {
			var states = get_states(country_id, 'select[name="billing_state"]');
		} else {
			$('select[name="billing_state"]').html('<option value="">Select State</option>');
		}
	});

	function get_states(country_id, target) {
    $.ajax({
        method: "GET",
		url: "<?php echo url('ajax_get_states'); ?>",
        dataType : 'json',
        data: {country_id: country_id},
        success: function(data) {
          state_html(data, target, true);
        }
      });
  }


  function state_html(data, target, show) {
    var html = '<option value="">Select State</option>';
    for(var i = 0; i < data.length; i++) {
      html += '<option value="' + data[i].zone_id + '">' + data[i].name + '</option>';
    }
    if(show && target != '')
      $(target).html(html);
    else
      return html;
  }

	</script>

	@endpush
	<div class="clear"></div>
	@endsection