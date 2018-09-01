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
					
						<div class="panel-group" id="accordion">
							<!-- USER -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title"><a href="#userPanel" data-toggle="collapse" data-parent="#accordion"> {{ t('Edit company') }} </a></h4>
								</div>
								<div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='userPanel') ? 'in' : '' }}" id="userPanel">
									<div class="panel-body">
										<form name="add_company" class="form-horizontal" role="form" method="POST" action="{{ route('update_company', ['company' => $company->id]) }}" enctype="multipart/form-data">
											{!! csrf_field() !!}
											{{ method_field('PUT') }}	
											
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
											<!-- name -->
											<div class="form-group required <?php echo (isset($errors) and $errors->has('name')) ? 'has-error' : ''; ?>">
												<label class="col-sm-3 control-label">{{ t('Name') }} <sup>*</sup></label>
												<div class="col-sm-9">
													<input name="name" type="text" class="form-control" placeholder="" value="{{ old('name', $company->name) }}">
												</div>
											</div>
                                            
                                            <!-- Description -->
											<div class="form-group required <?php echo (isset($errors) and $errors->has('description')) ? 'has-error' : ''; ?>">
												<label class="col-sm-3 control-label">{{ t('Description') }} <sup>*</sup></label>
												<div class="col-sm-9">
                                                    <textarea name="description" id="description" cols="30" rows="10" class="form-control"> {!! old('description', $company->description) !!}</textarea>
												</div>
											</div>
                                            
                                            <!-- Logo -->
                                            
											<div class="form-group required <?php echo (isset($errors) and $errors->has('name')) ? 'has-error' : ''; ?>">
												<label class="col-sm-3 control-label">{{ t('Logo') }} <sup>*</sup></label>
												<div class="col-sm-9">
                                                    <input name="logo" type="file" class="form-control">
                                                    <div style="margin-top: 20px;">
                                                        <img class="thumbnail img-responsive" src="{{ asset('storage/' . $company->logo) }}" alt="img" width="150">
                                                    </div>
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

@section('after_scripts')
@endsection
