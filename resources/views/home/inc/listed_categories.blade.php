<?php
// Default Map's values
$loc = [
	'show'       => false,
	'itemsCols'  => 3,
	'showButton' => false,
];
$map = ['show' => false];

// Get Admin Map's values
if (isset($citiesOptions)) {
	if (isset($citiesOptions['show_cities']) and $citiesOptions['show_cities'] == '1') {
		$loc['show'] = true;
	}
	if (isset($citiesOptions['items_cols']) and !empty($citiesOptions['items_cols'])) {
		$loc['itemsCols'] = (int)$citiesOptions['items_cols'];
	}
	if (isset($citiesOptions['show_post_btn']) and $citiesOptions['show_post_btn'] == '1') {
		$loc['showButton'] = true;
	}
	
	if (file_exists(config('larapen.core.maps.path') . config('country.icode') . '.svg')) {
		if (isset($citiesOptions['show_map']) and $citiesOptions['show_map'] == '1') {
			$map['show'] = true;
		}
	}
}
?>
@if ($loc['show'] || $map['show'])
@include('home.inc.spacer')
<div class="container">
	<div class="row">
		<div class="col-lg-12 page-content">
			<div class="inner-box">
				@if (!$map['show'])
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<h2 class="title-3 no-padding">
								<i class="icon-location-2"></i>&nbsp;{{ t('Choose a city') }}
							</h2>
						</div>
					</div>
				@endif
				<?php
				$leftClassCol = '';
				$ulCol = 'col-xs-3'; // Cities Columns
				$rightClassCol = '';
				
				if ($loc['show'] && $map['show']) {
					// Display the Cities & the Map
					$leftClassCol = 'col-lg-8 col-md-8 col-sm-12';
					$ulCol = 'col-xs-4';
					$rightClassCol = 'col-lg-3 col-md-3 col-sm-12';
					
					if ($loc['itemsCols'] == 2) {
						$leftClassCol = 'col-lg-6 col-md-6 col-sm-12';
						$ulCol = 'col-xs-6';
						$rightClassCol = 'col-lg-5 col-md-5 col-sm-12';
					}
					if ($loc['itemsCols'] == 1) {
						$leftClassCol = 'col-lg-3 col-md-3 col-sm-12';
						$ulCol = 'col-xs-12';
						$rightClassCol = 'col-lg-8 col-md-8 col-sm-12';
					}
				} else {
					if ($loc['show'] && !$map['show']) {
						// Display the Cities & Hide the Map
						$leftClassCol = 'col-lg-12 col-md-12 col-sm-12';
					}
					if (!$loc['show'] && $map['show']) {
						// Display the Map & Hide the Cities
						$rightClassCol = 'col-lg-12 col-md-12 col-sm-12';
					}
				}
				?>
				
				<div class="{{ $leftClassCol }} page-content no-margin no-padding">
					@if (isset($cities))
						<div class="relative location-content" style="text-align: center;">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12">
									<div>
										@if ($loc['show'] && $map['show'])
											<h2 class="title-3" style="white-space: nowrap;">
												<i class="icon-location-2"></i>&nbsp;{{ t('Choose a city or region') }}

											</h2>
										@endif
										<div class="row" style="padding: 0 10px 0 20px;">
                                            
                                            

                                            
@if (isset($categoriesOptions) and isset($categoriesOptions['type_of_display']))
    
<div class="list-categories-children styled">
        @foreach ($cats as $key => $col)
            <div class="col-md-4 col-sm-4 {{ (count($cats) == $key+1) ? 'last-column' : '' }}">
                @foreach ($col as $iCat)
                    
                    <?php
                        $randomId = '-' . substr(uniqid(rand(), true), 5, 5);
                    ?>
                
                    <div class="cat-list">
                        <h3 class="cat-title rounded">
                            <?php $attr = ['countryCode' => config('country.icode'), 'catSlug' => $iCat->slug]; ?>
                            <a href="{{ lurl(trans('routes.v-search-cat', $attr), $attr) }}">
                                <i class="{{ $iCat->icon_class or 'icon-ok' }}"></i>
                                {{ $iCat->name }} <span class="count"></span>
                            </a>
                            <span data-target=".cat-id-{{ $iCat->id . $randomId }}" data-toggle="collapse" class="btn-cat-collapsed collapsed">
                                <span class="icon-down-open-big"></span>
                            </span>
                        </h3>
                        <ul class="cat-collapse collapse in cat-id-{{ $iCat->id . $randomId }} long-list-home">
                            @if (isset($subCats) and $subCats->has($iCat->tid))
                                @foreach ($subCats->get($iCat->tid) as $iSubCat)
                                    <li>
                                        <?php $attr =  ['countryCode' => config('country.icode'), 'catSlug' => $iCat->slug, 'subCatSlug' => $iSubCat->slug]; ?>
                                        <a href="{{ lurl(trans('routes.v-search-subCat', $attr), $attr) }}">
                                            {{ $iSubCat->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
		
@endif




										</div>
									</div>
								</div>
							</div>
							
							@if ($loc['showButton'])
								@if (!auth()->check() and config('settings.single.guests_can_post_ads') != '1')
									<a class="btn btn-lg btn-add-listing" href="#quickLogin" data-toggle="modal">
										<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}
									</a>
								@else
									<a class="btn btn-lg btn-add-listing" href="{{ lurl('posts/create') }}" style="padding-left: 30px; padding-right: 30px; text-transform: none;">
										<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}
									</a>
								@endif
							@endif
	
						</div>
					@endif
				</div>
				
				
				@include('layouts.inc.tools.svgmap')
	
			</div>
		</div>
	</div>
</div>
@endif

@section('modal_location')
	@parent
	@if ($loc['show'] || $map['show'])
		@include('layouts.inc.modal.location')
	@endif
@endsection



@section('before_scripts')
	@parent
	@if (isset($categoriesOptions) and isset($categoriesOptions['max_sub_cats']) and $categoriesOptions['max_sub_cats'] >= 0)
		<script>
			var maxSubCats = {{ (int)$categoriesOptions['max_sub_cats'] }};
		</script>
	@endif
@endsection
