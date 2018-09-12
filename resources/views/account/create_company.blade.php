{{--
 * LaraClassified - Geo Classified Ads CMS
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
@extends('layouts.master')
@section('after_styles')
	<style>
		.skills-list{
			padding-left: 30px;	
			padding-right: 30px;	
		}

		ul.sub-skills{
			padding-left: 30px;
		}
	</style>
@endsection
@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				<div class="col-sm-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->

				<div class="col-sm-9 page-content">

					@include('flash::message')

					@if (isset($errors) and $errors->any())
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					

					<div class="inner-box">
					
						<div class="panel-group" id="accordion">
							<!-- Company -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a href="#userPanel" data-toggle="collapse" data-parent="#accordion"> {{ t('Add company') }} </a></h4>
								</div>
								<div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='userPanel') ? 'in' : '' }}" id="userPanel">
									<div class="panel-body">
										<form name="add_company" class="form-horizontal" role="form" method="POST" action="{{ route('add_company') }}" enctype="multipart/form-data">
											{!! csrf_field() !!}
											
											<input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
											<fieldset>
												<legend>Basic info</legend>
												<!-- name -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('name')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Name') }} <sup>*</sup></label>
													<div class="col-sm-9">
														<input name="name" type="text" class="form-control" placeholder="" value="{{ old('name') }}">
													</div>
												</div>
												
												<!-- Description -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('description')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Description') }} <sup>*</sup></label>
													<div class="col-sm-9">
														<textarea name="description" id="description" cols="30" rows="10" class="form-control"> {!! old('description') !!}</textarea>
													</div>
												</div>
												
												<!-- Logo -->
												
												<div class="form-group required <?php echo (isset($errors) and $errors->has('name')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Logo') }} <sup>*</sup></label>
													<div class="col-sm-9">
														<input name="logo" type="file" class="form-control">
														
													</div>
												</div>
											</fieldset>
											
											<fieldset>
												<legend>Contact info</legend>
												<!-- country_id -->
												<div id="cityBox" class="form-group required <?php echo (isset($errors) and $errors->has('city_id')) ? 'has-error' : ''; ?>">
													<label class="col-md-3 control-label" for="country_id">{{ t('Country') }} <sup>*</sup></label>
													<div class="col-md-9">
														<select id="countryId" name="country_id" class="form-control sselecter">
															<option value="0" {{ (!old('country_id') or old('country_id')==0) ? 'selected="selected"' : '' }}>
																{{ t('Select a country') }}
															</option>
															@foreach($countries as $country)
															<option value="{{ $country->id }}" data-code={{ $country->code }}>
																{{ $country->name }}
															</option>
															@endforeach
														</select>
													</div>
												</div>
												
												<!-- city_id -->
												<div id="cityBox" class="form-group required <?php echo (isset($errors) and $errors->has('city_id')) ? 'has-error' : ''; ?>">
													<label class="col-md-3 control-label" for="city_id">{{ t('City') }} <sup>*</sup></label>
													<div class="col-md-9">
														<select id="cityId" name="city_id" class="form-control sselecter">
															<option value="0" {{ (!old('city_id') or old('city_id')==0) ? 'selected="selected"' : '' }}>
																{{ t('Select a city') }}
															</option>
														</select>
													</div>
												</div>
												<!-- Address -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('address')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Address') }}</label>
													<div class="col-sm-9">
														<div class="input-group">
															<span class="input-group-addon"><i class="icon-location"></i></span>
															<input id="address" name="address" class="form-control" value="{{ old('address') }}" type="text">
														</div>			
													</div>
												</div>
												
												<!-- Phone -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('phone')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Phone') }}</label>
													<div class="col-sm-9">
														<div class="input-group">
															<span class="input-group-addon"><i class="icon-phone"></i></span>
															<input id="phone" name="phone" class="form-control" value="{{ old('phone') }}" type="text">
														</div>			
													</div>
												</div>
												
												<!-- Fax -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('fax')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Fax') }}</label>
													<div class="col-sm-9">
													<div class="input-group">			
															<span class="input-group-addon"><i class="icon-print"></i></span>
															<input id="fax" name="fax" class="form-control" value="{{ old('fax') }}" type="text">
														</div>			
													</div>
												</div>
												
												<!-- Email -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('email')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Email') }}</label>
													<div class="col-sm-9">
														<div class="input-group">
															<span class="input-group-addon"><i class="icon-mail"></i></span>
															<input id="email" name="email" class="form-control" value="{{ old('email') }}" type="text">
														</div>			
													</div>
												</div>
												
												<!-- Website -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('website')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Website') }}</label>
													<div class="col-sm-9">
														<div class="input-group">
															<span class="input-group-addon"><i class="icon-globe"></i></span>
															<input id="website" name="website" class="form-control" value="{{ old('website') }}" type="text">
														</div>			
													</div>
												</div>
												
												<!-- Facebook -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('facebook')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Facebook') }}</label>
													<div class="col-sm-9">
														<div class="input-group">
															<span class="input-group-addon">
																	<i class="icon-facebook fa"></i>
															</span>
															<input id="facebook" name="facebook" class="form-control" value="{{ old('facebook') }}" type="text">
														</div>			
													</div>
												</div>
											
											</fieldset>
											

											<fieldset>
												<legend>Skills</legend>
												<div class="row skills-list">
											
													@foreach($skills as $skill)
														<div class="col-md-6">
															<ul class="skills">
																<li>
																	<label class="checkbox-inline skill">
																		{{-- <input type="checkbox" class="parent_skill" id="skill-{{$skill->id}}"> --}}
																		{{ $skill->name }}
																	</label>
																	<ul class="sub-skills">
																		@foreach($skill->children as $sub_skill)
																		<li>
																			<label class="checkbox-inline skill">
																			<input name="skills[]" type="checkbox" value="{{ $sub_skill->id}}" class="skill-{{ $skill->id }}">
																				{{ $sub_skill->name }}
																			</label>
																		</li>
																		@endforeach
																	</ul>
																</li>
															</ul>	
														</div>
													@endforeach
											
												</div>
											</fieldset>
											
											<!-- Button -->
											<div class="form-group">
												<div class="col-sm-offset-3 col-sm-9">
													<button type="submit" class="btn btn-primary">{{ t('Continue') }}</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							
						</div>
						<!--/.row-box End-->

					</div>
				</div>
				<!--/.page-content-->
			</div>
			<!--/.row-->
		</div>
		<!--/.container-->
	</div>
	<!-- /.main-container -->
@endsection

@section('after_scripts')

<script>
	$("#countryId").change(function(){
		let code = $("#countryId option:selected").data('code');

		$('input#countryCode').val(code);

		console.log($('input#countryCode').val());
	});

	// $('input.parent_skill').change(function(e){
	// 	e.preventDefault();
	// 	if($(this).prop('checked')){
			
	// 		$('.' + $(this).attr('id')).each(function(){
	// 			$(this).attr('checked', 'checked');
	// 		})

	// 		alert("here");
	// 	}else{
			
	// 		$('.' + $(this).attr('id')).removeAttr('checked');
	// 	}
	// });
</script>
<script src="{{ url('assets/js/app/d.select.location.js') . vTime() }}"></script>	
@endsection
