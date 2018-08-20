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
use App\Http\Requests\Admin\PostTypeRequest as StoreRequest;
use App\Http\Requests\Admin\PostTypeRequest as UpdateRequest;

class PostTypeController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\PostType');
		$this->xPanel->setRoute(admin_uri('p_types'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.ad type'), trans('admin::messages.ad types'));
		$this->xPanel->enableDetailsRow();
		$this->xPanel->allowAccess(['details_row']);
		// $this->xPanel->denyAccess(['create', 'delete']);

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		$this->xPanel->addButtonFromModelFunction('line', 'custom_fields', 'customFieldsBtn', 'beginning');
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'  => "id",
			'label' => "ID",

		]);
		
		$this->xPanel->addColumn([
			'name'  => "name",
			'label' => trans("admin::messages.Name"),
		]);

		$this->xPanel->addColumn([
			'name'  => "is_pro",
			'label' => "is_pro",

		]);
		
		// FIELDS
		$this->xPanel->addField([
			'name'       => "name",
			'label'      => trans("admin::messages.Name"),
			'type'       => "text",
			'attributes' => [
				'placeholder' => trans("admin::messages.Name"),
			],
		]);
		
		$this->xPanel->addField([
			'name'       => "is_pro",
			'label'      => trans("admin::messages.Is_pro"),
			'type'       => "checkbox",
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
