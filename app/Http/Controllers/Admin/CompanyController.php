<?php
/**
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
 */

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\CategoryRequest as StoreRequest;
use App\Http\Requests\Admin\CategoryRequest as UpdateRequest;

class CompanyController extends PanelController
{
	public $userId = 0;
	
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Company');
		$this->xPanel->setRoute(admin_uri('my-companies'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.category'), trans('admin::messages.categories'));
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->enableDetailsRow();
		$this->xPanel->allowAccess(['reorder', 'details_row']);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft', 'ASC');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		$this->xPanel->addButtonFromModelFunction('line', 'custom_fields', 'customFieldsBtn', 'beginning');
		$this->xPanel->addButtonFromModelFunction('line', 'sub_categories', 'subCategoriesBtn', 'beginning');
		
		// Filters
		// -----------------------
		// $this->xPanel->addFilter([
		// 	'name'  => 'status',
		// 	'type'  => 'dropdown',
		// 	'label' => trans('admin::messages.Type'),
		// ], [
		// 	'classified'  => 'Classified',
		// 	'job-offer'   => 'Job Offer',
		// 	'job-search'  => 'Job Search',
		// 	'non-salable' => 'Non-Salable',
		// ], function ($value) {
		// 	$this->xPanel->addClause('where', 'type', '=', $value);
		// });
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'  => 'id',
			'label' => '',
			'type'  => 'checkbox',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
			'name'          => 'name',
			'label'         => trans("admin::messages.Name"),
			'type'          => 'model_function',
			'function_name' => 'getNameHtml',
		]);
	
		
		
		// FIELDS
		$this->xPanel->addField([
			'name'  => 'user_id',
			'type'  => 'hidden',
			'value' => auth()->user()->id,
		]);
		$this->xPanel->addField([
			'name'              => 'name',
			'label'             => trans("admin::messages.Name"),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans("admin::messages.Name"),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		
		$this->xPanel->addField([
			'name'       => 'description',
			'label'      => trans('admin::messages.Description'),
			'type'       => 'textarea',
			'attributes' => [
				'placeholder' => trans('admin::messages.Description'),
			],
        ]);
        
		$this->xPanel->addField([
			'name'   => 'logo',
			'label'  => trans('admin::messages.Logo'),
			'type'   => 'image',
			'upload' => true,
			'disk'   => 'public',
			'hint'   => trans('admin::messages.Used in the categories area on the homepage (Related to the type of display: "Picture as Icon").'),
		]);
		
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
