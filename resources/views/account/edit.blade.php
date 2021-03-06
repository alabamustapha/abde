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
						<div class="row">
							<div class="col-md-5 col-xs-4 col-xxs-12">
								<h3 class="no-padding text-center-480 useradmin">
									<a href="">
										@if($user->img_url)
											<img class="userImg" src="{{ url('storage/' . $user->img_url) }}" alt="user">&nbsp;
										@elseif (!empty($gravatar))
											<img class="userImg" src="{{ $gravatar }}" alt="user">&nbsp;
										@else
											<img class="userImg" src="{{ url('images/user.jpg') }}" alt="user">
										@endif
										{{ $user->name }}
									</a>
								</h3>
							</div>
							<div class="col-md-7 col-xs-8 col-xxs-12">
								<div class="header-data text-center-xs">
									<!-- Traffic data -->
									<div class="hdata">
										<div class="mcol-left">
											<!-- Icon with red background -->
											<i class="fa fa-eye ln-shadow"></i>
										</div>
										<div class="mcol-right">
											<!-- Number of visitors -->
											<p>
												<a href="{{ lurl('account/my-posts') }}">
													<?php $totalPostsVisits = (isset($countPostsVisits) and $countPostsVisits->total_visits) ? $countPostsVisits->total_visits : 0 ?>
													{{ \App\Helpers\Number::short($totalPostsVisits) }}
													<em>{{ trans_choice('global.count_visits', getPlural($totalPostsVisits)) }}</em>
												</a>
											</p>
										</div>
										<div class="clearfix"></div>
									</div>

									<!-- Ads data -->
									<div class="hdata">
										<div class="mcol-left">
											<!-- Icon with green background -->
											<i class="icon-th-thumb ln-shadow"></i>
										</div>
										<div class="mcol-right">
											<!-- Number of ads -->
											<p>
												<a href="{{ lurl('account/my-posts') }}">
													{{ \App\Helpers\Number::short($countPosts) }}
													<em>{{ trans_choice('global.count_posts', getPlural($countPosts)) }}</em>
												</a>
											</p>
										</div>
										<div class="clearfix"></div>
									</div>

									<!-- Favorites data -->
									<div class="hdata">
										<div class="mcol-left">
											<!-- Icon with blue background -->
											<i class="fa fa-user ln-shadow"></i>
										</div>
										<div class="mcol-right">
											<!-- Number of favorites -->
											<p>
												<a href="{{ lurl('account/favourite') }}">
													{{ \App\Helpers\Number::short($countFavoritePosts) }}
													<em>{{ trans_choice('global.count_favorites', getPlural($countFavoritePosts)) }} </em>
												</a>
											</p>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="inner-box">
						<div class="welcome-msg">
							<h3 class="page-sub-header2 clearfix no-padding">{{ t('Hello') }} {{ $user->name }} ! </h3>
							<span class="page-sub-header-sub small">
                                {{ t('You last logged in at') }}: {{ $user->last_login_at->formatLocalized(config('settings.app.default_datetime_format')) }}
                            </span>
						</div>
						<div class="panel-group" id="accordion">
							<!-- USER -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a href="#userPanel" data-toggle="collapse" data-parent="#accordion"> {{ t('My details') }} </a></h4>
								</div>
								<div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='userPanel') ? 'in' : '' }}" id="userPanel">
									<div class="panel-body">

										<form class="form-horizontal" id="postForm" method="POST" action="{{ url()->current() }}" enctype="multipart/form-data">
											{!! csrf_field() !!}
											<input type="hidden" name="id" value="{{ auth()->user()->id }}">
											<fieldset>
												<!-- Profile picture -->
												<div id="picturesBloc" class="form-group <?php echo (isset($errors) and $errors->has('pictures')) ? 'has-error' : ''; ?>">
													<label class="col-md-3 control-label" for="pictures"> {{ _('Profile picture') }} </label>
													<div class="col-md-8"> </div>
													<div class="col-md-12" style="position: relative; float: {!! (config('lang.direction')=='rtl') ? 'left' : 'right' !!}; padding-top: 10px; text-align: center;">
														<div {!! (config('lang.direction')=='rtl') ? 'dir="rtl"' : '' !!} class="file-loading mb10">
															<input id="pictureField" name="picture" type="file" multiple class="file picimg">
														</div>
														<p class="help-block">
															{{ _('Upload profile image') }}
														</p>
													</div>
												</div>
											
												<div id="uploadError" style="margin-top:10px; display:none"></div>
												<div id="uploadSuccess" class="alert alert-success fade in" style="margin-top:10px;display:none"></div>
											
												<div style="margin-bottom: 30px;"></div>
											
											</fieldset>
										</form>

										<form name="details" class="form-horizontal" role="form" method="POST" action="{{ url()->current() }}">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="userPanel">

											<!-- gender_id -->
											<div class="form-group required <?php echo (isset($errors) and $errors->has('gender_id')) ? 'has-error' : ''; ?>">
												<label class="col-md-3 control-label">{{ t('Gender') }}</label>
												<div class="col-md-9">
													@if ($genders->count() > 0)
                                                        @foreach ($genders as $gender)
                                                            <label class="radio-inline" for="gender_id">
                                                                <input name="gender_id" id="gender_id-{{ $gender->tid }}" value="{{ $gender->tid }}"
                                                                       type="radio" {{ (old('gender_id', $user->gender_id)==$gender->tid) ? 'checked="checked"' : '' }}>
                                                                {{ $gender->name }}
                                                            </label>
                                                        @endforeach
													@endif
												</div>
											</div>
												
											<!-- name -->
											<div class="form-group required <?php echo (isset($errors) and $errors->has('name')) ? 'has-error' : ''; ?>">
												<label class="col-sm-3 control-label">{{ t('Name') }} <sup>*</sup></label>
												<div class="col-sm-9">
													<input name="name" type="text" class="form-control" placeholder="" value="{{ old('name', $user->name) }}">
												</div>
											</div>
											
											<!-- username -->
											<div class="form-group required <?php echo (isset($errors) and $errors->has('username')) ? 'has-error' : ''; ?>">
												<label class="col-sm-3 control-label" for="email">{{ t('Username') }} <sup>*</sup></label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon"><i class="icon-user"></i></span>
														<input id="username" name="username" type="text" class="form-control" placeholder="{{ t('Username') }}"
															   value="{{ old('username', $user->username) }}">
													</div>
												</div>
											</div>
												
											<!-- email -->
											<div class="form-group required <?php echo (isset($errors) and $errors->has('email')) ? 'has-error' : ''; ?>">
												<label class="col-sm-3 control-label">{{ t('Email') }} <sup>*</sup></label>
												<div class="col-sm-9">
													<div class="input-group">
														<span class="input-group-addon"><i class="icon-mail"></i></span>
														<input id="email" name="email" type="email" class="form-control" placeholder="{{ t('Email') }}" value="{{ old('email', $user->email) }}">
													</div>
												</div>
											</div>
                                                
                                            <!-- country_code -->
                                            <?php
                                            /*
											<div class="form-group required <?php echo (isset($errors) and $errors->has('country_code')) ? 'has-error' : ''; ?>">
												<label class="col-md-3 control-label" for="country_code">{{ t('Your Country') }} <sup>*</sup></label>
												<div class="col-md-9">
													<select name="country_code" class="form-control sselecter">
														<option value="0" {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>
															{{ t('Select a country') }}
														</option>
														@foreach ($countries as $item)
															<option value="{{ $item->get('code') }}" {{ (old('country_code', $user->country_code)==$item->get('code')) ? 'selected="selected"' : '' }}>
																{{ $item->get('name') }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
                                            */
                                            ?>
                                            <input name="country_code" type="hidden" value="{{ $user->country_code }}">
												
											<!-- phone -->
											<div class="form-group required <?php echo (isset($errors) and $errors->has('phone')) ? 'has-error' : ''; ?>">
												<label for="phone" class="col-sm-3 control-label">{{ t('Phone') }} <sup>*</sup></label>
												<div class="col-sm-7">
													<div class="input-group">
														<span id="phoneCountry" class="input-group-addon">{!! getPhoneIcon(old('country_code', $user->country_code)) !!}</span>
														
														<input id="phone" name="phone" type="text" class="form-control"
															   placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }}"
															   value="{{ phoneFormat(old('phone', $user->phone), old('country_code', $user->country_code)) }}">
														
														<label class="input-group-addon">
															<input name="phone_hidden" id="phoneHidden" type="checkbox"
																   value="1" {{ (old('phone_hidden', $user->phone_hidden)=='1') ? 'checked="checked"' : '' }}>
															{{ t('Hide') }}
														</label>
													</div>
												</div>
											</div>

												<input type="hidden" name="countryCode" value="US" id="countryCode">
												
												<!-- country_id -->
												<div id="cityBox" class="form-group required <?php echo (isset($errors) and $errors->has('city_id')) ? 'has-error' : ''; ?>">
													<label class="col-md-3 control-label" for="country_id">{{ t('Country') }} <sup>*</sup></label>
													<div class="col-md-9">
														<select id="countryId" name="country_id" class="form-control sselecter">
															<option value="0" {{ (!old('country_id') or old('country_id')==0) ? 'selected="selected"' : '' }}>
																{{ t('Select a country') }}
															</option>
															@foreach($countries as $country)
															<option value="{{ $country->id }}" {{ $user->country_id == $country->id ? 'selected="selected"' : '' }} data-code={{ $country->code }}>
																{{ $country->name }}
															</option>
															@endforeach
														</select>
													</div>
												</div>
												
												<!-- city_id -->
												<div id="cityBox" class="form-group required <?php echo (isset($errors) and $errors->has('city_id')) ? 'has-error' : ''; ?>">
													<label class="col-md-3 control-label" for="city_id">{{ t('City') }} </label>
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
															<input id="address" name="address" class="form-control" value="{{ $user->address}}" type="text">
														</div>			
													</div>
												</div>
												
												<!-- profession -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('profession')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Profession') }}</label>
													<div class="col-sm-9">
													<div class="input-group">			
															<span class="input-group-addon"><i class="icon-print"></i></span>
															<input id="profession" name="profession" class="form-control" value="{{ $user->profession }}" type="text">
														</div>			
													</div>
												</div>
												
												
												<!-- Website -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('website')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('Website') }}</label>
													<div class="col-sm-9">
														<div class="input-group">
															<span class="input-group-addon"><i class="icon-globe"></i></span>
															<input id="website" name="website" class="form-control" value="{{ $user->website }}" type="text">
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
															<input id="facebook" name="facebook" class="form-control" value="{{ $user->facebook }}" type="text">
														</div>			
													</div>
												</div>
												
												<!-- About -->
												<div class="form-group required <?php echo (isset($errors) and $errors->has('about')) ? 'has-error' : ''; ?>">
													<label class="col-sm-3 control-label">{{ t('About me') }}</label>
													<div class="col-sm-9">
														<textarea class="form-control" name="about" id="about" cols="30" rows="10">{{ $user->about }}</textarea>
													</div>
												</div>
												

											<div class="form-group">
												<div class="col-sm-offset-3 col-sm-9"></div>
											</div>
											
											<!-- Button -->
											<div class="form-group">
												<div class="col-sm-offset-3 col-sm-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							
							<!-- SETTINGS -->
							<div class="panel panel-default" style="display: none">
								<div class="panel-heading">
									<h4 class="panel-title"><a href="#settingsPanel" data-toggle="collapse" data-parent="#accordion"> {{ t('Settings') }} </a></h4>
								</div>
								<div class="panel-collapse collapse {{ (old('panel')=='settingsPanel') ? 'in' : '' }}" id="settingsPanel">
									<div class="panel-body">
										<form name="settings" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/settings') }}">
											{!! csrf_field() !!}
											<input name="_method" type="hidden" value="PUT">
											<input name="panel" type="hidden" value="settingsPanel">
											
											<!-- disable_comments -->
											<div class="form-group">
												<div class="col-sm-12">
													<div class="checkbox">
														<label>
															<input id="disable_comments" name="disable_comments" value="1" type="checkbox" {{ ($user->disable_comments==1) ? 'checked' : '' }}>
															{{ t('Disable comments on my ads') }}
														</label>
													</div>
												</div>
											</div>
											
											<!-- password -->
											<div class="form-group <?php echo (isset($errors) and $errors->has('password')) ? 'has-error' : ''; ?>">
												<label class="col-sm-3 control-label">{{ t('New Password') }}</label>
												<div class="col-sm-9">
													<input id="password" name="password" type="password" class="form-control" placeholder="{{ t('Password') }}">
												</div>
											</div>
											
											<!-- password_confirmation -->
											<div class="form-group <?php echo (isset($errors) and $errors->has('password')) ? 'has-error' : ''; ?>">
												<label class="col-sm-3 control-label">{{ t('Confirm Password') }}</label>
												<div class="col-sm-9">
													<input id="password_confirmation" name="password_confirmation" type="password"
														   class="form-control" placeholder="{{ t('Confirm Password') }}">
												</div>
											</div>
											
											<!-- Button -->
											<div class="form-group">
												<div class="col-sm-offset-3 col-sm-9">
													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
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

@section('after_styles')
    <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
	@if (config('lang.direction') == 'rtl')
		<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
	@endif
    <style>
        .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
            box-shadow: 0 0 5px 0 #666666;
        }
		.file-loading:before {
			content: " {{ t('Loading') }}...";
		}
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    @if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js'))
        <script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js') }}" type="text/javascript"></script>
    @endif
   
	<script>
			/* Initialize with defaults (pictures) */
			
			<?php
				// Get Upload Url
				$uploadUrl = lurl('account/profile_image/');
			?>
				$('#pictureField').fileinput(
				{
					language: '{{ config('app.locale') }}',
					@if (config('lang.direction') == 'rtl')
						rtl: true,
					@endif
					overwriteInitial: true,
					showCaption: false,
					showPreview: true,
					allowedFileExtensions: {!! getUploadFileTypes('image', true) !!},
					uploadUrl: '{{ $uploadUrl }}',
					uploadAsync: false,
					showBrowse: false,
					showCancel: true,
					showUpload: true,
					showRemove: false,
					maxFileSize: {{ (int)config('settings.upload.max_file_size', 1000) }},
					browseOnZoneClick: true,
					minFileCount: 0,
					maxFileCount: 1,
					autoReplace: true,
					validateInitialCount: true,
					uploadClass: 'btn btn-success',
					elErrorContainer: '#uploadError',
					initialPreview: [ "{{ url('storage/' . $user->img_url) }}" ],
					initialPreviewAsData: true,
					initialPreviewFileType: 'image',
				
				});
	

			    $('#pictureField').on('fileuploaded', function(event, data, previewId, index) {
					var form = data.form, files = data.files, extra = data.extra,
						response = data.response, reader = data.reader;
					console.log('File uploaded triggered');
				});

		</script>

		
<script>
	$("#countryId").change(function(){
		let code = $("#countryId option:selected").data('code');

		$('input#countryCode').val(code);

		console.log($('input#countryCode').val());
	});
</script>
<script src="{{ url('assets/js/app/d.select.location.js') . vTime() }}"></script>	
    
@endsection

