@extends('layouts.'.$loginUser) @section('content') @php $pageTitle
="product"; @endphp

<div class="col-md-12">
	@include('admin.common.flash')
	<!--begin::Portlet-->
	<div class="m-portlet m-portlet--tab">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<span class="m-portlet__head-icon m--hide"> <i class="la la-truck"></i>
					</span>
					<h3 class="m-portlet__head-text">Product</h3>
				</div>
			</div>
		</div>

		<div class="m-portlet__body">
			<ul class="nav nav-tabs  m-tabs-line m-tabs-line--primary"
				role="tablist">
				<li class="nav-item m-tabs__item"><a
					class="nav-link m-tabs__link active" data-toggle="tab"
					href="#product_details" role="tab">Product Details</a></li>
				<li class="nav-item dropdown m-tabs__item"><a
					class="nav-link m-tabs__link" data-toggle="tab"
					href="#image_details" role="tab">Product Images/Videos</a></li>
				<li class="nav-item dropdown m-tabs__item"><a
					class="nav-link m-tabs__link" data-toggle="tab"
					href="#attr_details" role="tab">Attribute Details</a></li>
				<li class="nav-item m-tabs__item"><a class="nav-link m-tabs__link"
					data-toggle="tab" href="#shipping_details" role="tab">Shippng
						Details</a></li>
				<li class="nav-item m-tabs__item"><a class="nav-link m-tabs__link"
					data-toggle="tab" href="#policy_details" role="tab">Policy Details</a>
				</li>
			</ul>
			<form class="m-form m-form--fit">
				<div class="tab-content">
					<div class="tab-pane active" id="product_details" role="tabpanel">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group m-form__group">
									<label>Vendor Name</label>
									<p class="form-control-static">{{ $product->vendor_name }}</p>
								</div>
								<div class="form-group m-form__group">
									<label>Brand Name</label>
									<p class="form-control-static">{{ $product->brand_name }}</p>
								</div>
								<div class="form-group m-form__group">
									<label>Description</label>
									<p class="form-control-static">{{ $product->long_description }}</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group m-form__group">
									<label>Product Title</label>
									<p class="form-control-static">{{ $product->product_title }}</p>
								</div>
								<div class="form-group m-form__group">
									<label>Category Name</label>
									<p class="form-control-static"></p>
								</div>
								<div class="form-group m-form__group">
									<label>Description In Arabic</label>
									<p class="form-control-static">{{
										$product->long_description_arabic }}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="image_details" role="tabpanel">
						<div class="row">
							@foreach($productImage as $images)
								<div class="col-md-3">
									<div class="form-group m-form__group">
										<p class="form-control-static">
											<img src=" {{url('doc/product_image').'/'.$images->image_url }}"
												 width="50" height="50">
										</p>
									</div>
								</div>
							@endforeach
						</div>
					</div>
					<div class="tab-pane" id="attr_details" role="tabpanel">
						@foreach($productAttr as $value)
						<div class="row">
							<div class="col-md-6">
								<div class="form-group m-form__group">
									<label>Attribute Name</label>
									<p class="form-control-static">{{ $value['attribute_name'] }}</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group m-form__group">
									<label>Attribute Option</label>
									<p class="form-control-static">{{ $value['attribute_value'] }}</p>
								</div>
							</div>
						</div>

						@endforeach @foreach($productAttrCombination as $list)
						<div class="m-demo" data-code-preview="true" data-code-html="true"
							data-code-js="false">
							<div class="m-demo__preview">
								<h4>{{$list['combination_title']}}</h4>
								<div class="col-md-15">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group m-form__group">
												<label>Quantity</label>
												<p class="form-control-static">{{ $list['quantity'] }}</p>
											</div>
											<div class="form-group m-form__group">
												<label>Discount</label>
												<p class="form-control-static">{{
													$list['discount_percentage'] }}</p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group m-form__group">
												<label>Price</label>
												<p class="form-control-static">{{ $list['rate'] }}</p>
											</div>
											<div class="form-group m-form__group">
												<label>Discount Price</label>
												<p class="form-control-static">{{ $list['discount_price'] }}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
					<div class="tab-pane" id="shipping_details" role="tabpanel">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group m-form__group">
									<label>Shipping Class</label>
									<p class="form-control-static">{{ $productShipping[0]->shipping_class }}</p>
								</div>
							</div>
							@foreach($productShipping as $item)
								<div class="m-demo" data-code-preview="true" data-code-html="true"
									 data-code-js="false">
									<div class="m-demo__preview">
										<div class="col-md-15">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group m-form__group">
														<label>Country</label>
														<p class="form-control-static">{{ $item->country_name }}</p>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group m-form__group">
														<label>City</label>
														<p class="form-control-static">{{ $item->city_name }}</p>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group m-form__group">
														<label>Shipping Charges</label>
														<p class="form-control-static">{{ $item->shipping_charge }}</p>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group m-form__group">
														<label>Delivery Day 1</label>
														<p class="form-control-static">{{ $item->delivery_day_1 }}</p>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group m-form__group">
														<label>Delivery Day 2</label>
														<p class="form-control-static">{{ $item->delivery_day_2 }}</p>
													</div>
												</div>

											</div>
										</div>
									</div>
								</div>
							@endforeach
							<div class="col-md-6">
								<div class="form-group m-form__group">
									<label>Weight</label>
									<p class="form-control-static">{{ $product->weight }}</p>
								</div>
								<div class="form-group m-form__group">
									<label>Height</label>
									<p class="form-control-static">{{ $product->height }}</p>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group m-form__group">
									<label>Length</label>
									<p class="form-control-static">{{ $product->length }}</p>
								</div>
								<div class="form-group m-form__group">
									<label>Width</label>
									<p class="form-control-static">{{ $product->width }}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="policy_details" role="tabpanel">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group m-form__group">
									<label>Return Policy</label>
									<p class="form-control-static">{{ $product->return }}</p>
								</div>
								<div class="form-group m-form__group">
									<label>Return Policy Description</label>
									<p class="form-control-static">{{ $product->return_description
										}}</p>
								</div>

							</div>
							<div class="col-md-6">
								<div class="form-group m-form__group">
									<label>Exchange Policy</label>
									<p class="form-control-static">{{ $product->exchange }}</p>
								</div>
								<div class="form-group m-form__group">
									<label>Exchange Policy Description</label>
									<p class="form-control-static">{{
										$product->exchange_description }}</p>
								</div>
							</div>
						</div>

					</div>
				</div>
			</form>


			<div class="m-separator m-separator--dashed"></div>
		</div>
	</div>
	<!--end::Portlet-->
</div>


@endsection
