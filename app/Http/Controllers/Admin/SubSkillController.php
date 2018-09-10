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

use App\Http\Requests\Admin\SkillRequest as StoreRequest;
use App\Http\Requests\Admin\SkillRequest as UpdateRequest;
use App\Models\Skill;
use Larapen\Admin\app\Http\Controllers\PanelController;

class SubSkillController extends PanelController
{
	public $parentId = null;
	
	public function setup()
	{
		// Get the Parent ID
		$this->parentId = request()->segment(3);
		
		// Get Parent Category name
		$parent = Skill::findTransOrFail($this->parentId);
		
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Skill');
		$this->xPanel->setRoute(admin_uri('skills/' . $this->parentId . '/subskills'));
		$this->xPanel->setEntityNameStrings(
			trans('admin::messages.subskill') . ' &rarr; ' . '<strong>' . $parent->name . '</strong>',
			trans('admin::messages.subskills') . ' &rarr; ' . '<strong>' . $parent->name . '</strong>'
		);
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->enableDetailsRow();
		if (!request()->input('order')) {
			$this->xPanel->orderBy('lft', 'ASC');
		}
		
		$this->xPanel->enableParentEntity();
		$this->xPanel->setParentKeyField('parent_id');
		$this->xPanel->addClause('where', 'parent_id', '=', $this->parentId);
		$this->xPanel->setParentRoute(admin_uri('skills'));
		$this->xPanel->setParentEntityNameStrings('parent ' . trans('admin::messages.skill'), 'parent ' . trans('admin::messages.skills'));
		$this->xPanel->allowAccess(['reorder', 'details_row', 'parent']);
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		
		
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
		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans("admin::messages.Active"),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
			'on_display'    => 'checkbox',
		]);
		
		
		// FIELDS
		$this->xPanel->addField([
			'name'  => 'parent_id',
			'type'  => 'hidden',
			'value' => $this->parentId,
		], 'create');
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
			'name'              => 'slug',
			'label'             => trans("admin::messages.Slug"),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin::messages.Will be automatically generated from your name, if left empty.'),
			],
			'hint'              => trans('admin::messages.Will be automatically generated from your name, if left empty.'),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
	
		$this->xPanel->addField([
			'name'  => 'active',
			'label' => trans("admin::messages.Active"),
			'type'  => 'checkbox',
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
