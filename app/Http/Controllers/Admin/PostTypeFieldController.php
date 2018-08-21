<?php
/**
 * LaraClassified - Geo Classified Ads Software
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

use App\Models\Field;
use App\Models\PostType;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\PostTypeFieldRequest as StoreRequest;
use App\Http\Requests\Admin\PostTypeFieldRequest as UpdateRequest;

class PostTypeFieldController extends PanelController
{
	public $parentEntity = null;
	private $postTypeId = null;
	private $fieldId = null;
	
	public function setup()
	{
		// Parents Entities
		$parentEntities = ['p_types', 'custom_fields'];
		
		// Get the parent Entity slug
		$this->parentEntity = request()->segment(2);
		if (!in_array($this->parentEntity, $parentEntities)) {
			abort(404);
		}
		
		// PostType => PostTypeField
		if ($this->parentEntity == 'p_types') {
			$this->postTypeId = request()->segment(3);
			
			// Get Parent Category's name
			$post_type = PostType::findTransOrFail($this->postTypeId);
		}
		
		// Field => PostTypeField
		if ($this->parentEntity == 'custom_fields') {
			$this->fieldId = request()->segment(3);
			
			// Get Field's name
			$field = Field::findTransOrFail($this->fieldId);
		}
		
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\PostTypeField');
		$this->xPanel->with(['post_type', 'field']);
		$this->xPanel->enableParentEntity();
		
		// Category => CategoryField
		if ($this->parentEntity == 'p_types') {
			$this->xPanel->setRoute(admin_uri('p_types/' . $post_type->id . '/custom_fields'));
			$this->xPanel->setEntityNameStrings(
				trans('admin::messages.custom field') . ' &rarr; ' . '<strong>' . $post_type->name . '</strong>',
				trans('admin::messages.custom fields') . ' &rarr; ' . '<strong>' . $post_type->name . '</strong>'
			);
			$this->xPanel->enableReorder('field.name', 1);
			if (!request()->input('order')) {
				$this->xPanel->orderBy('lft', 'ASC');
			}
			$this->xPanel->setParentKeyField('post_type_id');
			$this->xPanel->addClause('where', 'post_type_id', '=', $post_type->id);
			$this->xPanel->setParentRoute(admin_uri('p_types'));
			$this->xPanel->setParentEntityNameStrings(trans('admin::messages.post_type'), trans('admin::messages.post_types'));
			$this->xPanel->allowAccess(['reorder', 'parent']);
		}
		
		// Field => CategoryField
		if ($this->parentEntity == 'custom_fields') {
			$this->xPanel->setRoute(admin_uri('custom_fields/' . $field->id . '/p_types'));
			$this->xPanel->setEntityNameStrings(
				'<strong>' . $field->name . '</strong> ' . trans('admin::messages.custom field') . ' &rarr; ' . trans('admin::messages.post_type'),
				'<strong>' . $field->name . '</strong> ' . trans('admin::messages.custom fields') . ' &rarr; ' . trans('admin::messages.post_types')
			);
			$this->xPanel->setParentKeyField('field_id');
			$this->xPanel->addClause('where', 'field_id', '=', $field->id);
			$this->xPanel->setParentRoute(admin_uri('custom_fields'));
			$this->xPanel->setParentEntityNameStrings(trans('admin::messages.custom field'), trans('admin::messages.custom fields'));
			$this->xPanel->allowAccess(['parent']);
		}
		
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
		
		// PostType => PostTypeField
		if ($this->parentEntity == 'p_types') {
			$this->xPanel->addColumn([
				'name'          => 'field_id',
				'label'         => mb_ucfirst(trans("admin::messages.custom field")),
				'type'          => 'model_function',
				'function_name' => 'getFieldHtml',
			]);
		}
		
		// Field => PostTypeField
		if ($this->parentEntity == 'custom_fields') {
			$this->xPanel->addColumn([
				'name'          => 'post_type_id',
				'label'         => trans("admin::messages.PostType"),
				'type'          => 'model_function',
				'function_name' => 'getPostTypeHtml',
			]);
		}
		
		// $this->xPanel->addColumn([
		// 	'name'          => 'disabled_in_subcategories',
		// 	'label'         => trans("admin::messages.Disabled in subcategories"),
		// 	'type'          => 'model_function',
		// 	'function_name' => 'getDisabledInSubCategoriesHtml',
		// 	'on_display'    => 'checkbox',
		// ]);
		
		
		// FIELDS
		// PostType => PostTypeField
		if ($this->parentEntity == 'p_types') {
			$this->xPanel->addField([
				'name'  => 'post_type_id',
				'type'  => 'hidden',
				'value' => $this->postTypeId,
			], 'create');
			$this->xPanel->addField([
				'name'        => 'field_id',
				'label'       => mb_ucfirst(trans("admin::messages.Select a Custom field")),
				'type'        => 'select2_from_array',
				'options'     => $this->fields($this->fieldId),
				'allows_null' => false,
			]);
		}
		
		// Field => PostTypeField
		if ($this->parentEntity == 'custom_fields') {
			$this->xPanel->addField([
				'name'  => 'field_id',
				'type'  => 'hidden',
				'value' => $this->fieldId,
			], 'create');
			$this->xPanel->addField([
				'name'        => 'post_type_id',
				'label'       => trans("admin::messages.Select a Post Type"),
				'type'        => 'select2_from_array',
				'options'     => $this->post_types($this->postTypeId),
				'allows_null' => false,
			]);
		}
		
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
	
	private function fields($selectedEntryId)
	{
		$entries = Field::trans()->orderBy('name')->get();
		
		return $this->getTranslatedArray($entries, $selectedEntryId);
	}
	
	private function post_types($selectedEntryId)
	{
		$entries = PostType::trans()->where('parent_id', 0)->orderBy('lft')->get();
		if ($entries->count() <= 0) {
			return [];
		}
		
		$tab = [];
		foreach ($entries as $entry) {
			$tab[$entry->tid] = $entry->name;
			
			$subEntries = PostType::trans()->where('parent_id', $entry->id)->orderBy('lft')->get();
			if ($subEntries->count() > 0) {
				foreach ($subEntries as $subEntry) {
					$tab[$subEntry->tid] = "---| " . $subEntry->name;
				}
			}
		}
		
		return $tab;
	}
}
