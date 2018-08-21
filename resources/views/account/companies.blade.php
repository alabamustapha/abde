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

				@if (Session::has('flash_notification'))
					<div class="container" style="margin-bottom: -10px; margin-top: -10px;">
						<div class="row">
							<div class="col-lg-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-sm-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->

				<div class="col-sm-9 page-content">
					<div class="inner-box">
						@if ($pagePath=='my-posts')
							<h2 class="title-2"><i class="icon-docs"></i> {{ t('My Ads') }} </h2>
						@elseif ($pagePath=='archived')
							<h2 class="title-2"><i class="icon-folder-close"></i> {{ t('Archived ads') }} </h2>
						@elseif ($pagePath=='favourite')
							<h2 class="title-2"><i class="icon-heart-1"></i> {{ t('Favourite ads') }} </h2>
						@elseif ($pagePath=='pending-approval')
							<h2 class="title-2"><i class="icon-hourglass"></i> {{ t('Pending approval') }} </h2>
						@else
							<h2 class="title-2"><i class="icon-docs"></i> {{ t('Create a Company') }} </h2>
						@endif

						<div class="row" style="margin-bottom: 10px;">
							<div class="col-md-12">
									<a class="btn btn-lg btn-default" href="{{ lurl('account/my-companies/create') }}">
										<i class="fa fa-plus"></i> {{ t('Add new') }}
									</a>
							</div>	
						</div>


						<div class="table-responsive">
							{{-- <form name="listForm" method="POST" action="{{ lurl('account/'.$pagePath.'/delete') }}"> --}}
								{!! csrf_field() !!}
								{{-- <div class="table-action">
									<label for="checkAll">
										<input type="checkbox" id="checkAll">
										{{ t('Select') }}: {{ t('All') }} |
										<button type="submit" class="btn btn-sm btn-default delete-action">
											<i class="fa fa-trash"></i> {{ t('Delete') }}
										</button>
									</label>
									<div class="table-search pull-right col-xs-7">
										<div class="form-group">
											<label class="col-xs-5 control-label text-right">{{ t('Search') }} <br>
												<a title="clear filter" class="clear-filter" href="#clear">[{{ t('clear') }}]</a> </label>
											<div class="col-xs-7 searchpan">
												<input type="text" class="form-control" id="filter">
											</div>
										</div>
									</div>
								</div> --}}
								
								<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true">
									<thead>
									<tr>
										<th data-type="numeric" data-sort-initial="true"></th>
										<th>{{ t('Logo') }}</th>
										<th data-sort-ignore="true">{{ t('Company name') }}</th>
										<th data-sort-ignore="true">{{ t('Description') }}</th>
                                        <th data-type="numeric">{{ t('Posts')}}</th>
										<th>{{ t('Option') }}</th>
									</tr>
									</thead>
									<tbody>

									
									@if(isset($companies) && $companies->count() > 0)
										
											@foreach($companies as $key => $company)
																					
										
												<tr>
													<td style="width:2%" class="add-img-selector">
														<div class="checkbox">
															<label><input type="checkbox" name="entries[]" value="{{ $company->id }}"></label>
														</div>
													</td>
													<td style="width:14%" class="add-img-td">
														<a href="{{ lurl('account/my-companies/'.$company->id.'/edit') }}"><img class="thumbnail img-responsive" src="{{ asset('storage/' . $company->logo) }}" alt="img"></a>
													</td>
													
													<td style="width:25%" class="ads-details-td">
														<div>
															<p>
																<strong>
																	<a href="{{ lurl('account/my-companies/'.$company->id.'/edit') }}" title="{{ $company->name }}">{{ str_limit($company->name, 40) }}</a>
																</strong>
																
															</p>
																		
														</div>
													</td>
													
													<td style="width:58%" class="ads-details-td">
														<div>
															<p>
																{{ $company->description }}
															</p>
																		
														</div>
													</td>
													
													<td style="width:16%" class="price-td">
														<div>
															<strong>
																{{ $company->posts->count() }}
															</strong>
														</div>
													</td>
													<td style="width:10%" class="action-td">
														<div>
															
																<a class="btn btn-primary btn-sm edit-action" href="{{ lurl('account/my-companies/'.$company->id.'/edit') }}">
																	<i class="fa fa-trash"></i> {{ t('Edit') }}
																</a>
																<form action="{{ lurl('account/my-companies/'.$company->id) }}" id="company-{{ $company->id }}" method="POST">
																	{{ csrf_field() }}	
																	{{ method_field('DELETE') }}	
																	<button class="btn btn-danger btn-sm delete-company-action" data-company-id="{{ $company->id }}">
																		<i class="fa fa-trash"></i> {{ t('Delete') }}
																	</button>
																</form>
															
														</div>
													</td>
												</tr>

											@endforeach
                                    @endif
									
								</tbody>
								</table>
							{{-- </form> --}}
						</div>
                            
                        <div class="pagination-bar text-center">
                            {{ (isset($posts)) ? $posts->links() : '' }}
                        </div>

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
	<script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$(function () {
			$('#addManageTable').footable().bind('footable_filtering', function (e) {
				var selected = $('.filter-status').find(':selected').text();
				if (selected && selected.length > 0) {
					e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
					e.clear = !e.filter;
				}
			});

			$('.clear-filter').click(function (e) {
				e.preventDefault();
				$('.filter-status').val('');
				$('table.demo').trigger('footable_clear_filter');
			});

			$('#checkAll').click(function () {
				checkAll(this);
			});
			
			// $('a.delete-action, button.delete-action').click(function(e)
			// {
			// 	e.preventDefault(); /* prevents the submit or reload */
			// 	var confirmation = confirm("{{ t('Are you sure you want to perform this action?') }}");
				
			// 	if (confirmation) {
			// 		if( $(this).is('a') ){
			// 			var url = $(this).attr('href');
			// 			if (url !== 'undefined') {
			// 				redirect(url);
			// 			}
			// 		} else {
			// 			$('form#company-' + $(this).data('companyId')).submit();
			// 			// $('form[name=listForm]').submit();
			// 		}
					
			// 	}
				
			// 	return false;
			// });
			
			$('button.delete-company-action').click(function(e)
			{
				e.preventDefault(); /* prevents the submit or reload */
				var confirmation = confirm("{{ t('Are you sure you want to perform this action?') }}");
				
				if (confirmation) {
					alert("here");
						// $('form#company-' + $(this).data('companyId')).submit();
					}
					
				}
				
				return false;
			});
		});
	</script>
	<!-- include custom script for ads table [select all checkbox]  -->
	<script>
		function checkAll(bx) {
			var chkinput = document.getElementsByTagName('input');
			for (var i = 0; i < chkinput.length; i++) {
				if (chkinput[i].type == 'checkbox') {
					chkinput[i].checked = bx.checked;
				}
			}
		}
	</script>
@endsection
