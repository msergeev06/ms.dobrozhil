<?php

namespace Ms\Dobrozhil\Ui;

use Ms\Core\Entity\System\Application;
use Ms\Core\Entity\System\Multiton;

class AdminPanel extends Multiton
{
	private $arObjectsPagesDefaultSef = [
		//ms:dobrozhil.obj
		'root_path' => '/objects/',
		'class_add' => 'class_add/',
		'object_add' => 'object_add/',
		'class_view' => 'class/#CLASS_NAME#/',
		//ms:dobrozhil.obj.class.view
		'class_properties_list' => 'properties/list/',
		'class_methods_list' => 'methods/list/',
		'class_objects_list' => 'objects/list/',
		'class_export' => 'export/',
		'class_export_full' => 'export_full/',
		//ms:dobrozhil.obj.class.view.properties
		'class_properties' => 'properties/',
		'class_property_add' => 'add/',
		'class_property_edit' => 'edit/#PROPERTY_NAME#/',
		'class_property_delete' => 'delete/#PROPERTY_NAME#/',

		'class_add_child' => 'add_child/',
		'class_delete' => 'delete/',
		'class_edit' => 'edit/',
		'class_access' => 'access/',
		'class_methods' => 'methods/',
		'class_method_add' => 'add/',
		'class_method_edit' => 'edit/#METHOD_NAME#/',
		'class_objects' => 'objects/',
		'class_object_add' => 'add/',
		'class_object_edit' => 'edit/#OBJECT_NAME#/',

		//ms:dobrozhil.scripts
		'script_category_add' => 'category/new/',
		'script_category_edit' => 'category/edit/#CATEGORY_ID#/',
		'script_script_add' => 'script/new/',
		'script_script_edit' => 'script/edit/#SCRIPT_NAME#/',

    ];
	private $arObjectsPagesDefault = [
		//ms:dobrozhil.obj
			'root_path' => '/objects/',
			'class_add' => '?page=class_add',
			'object_add' => '?page=object_add&class=#CLASS_NAME#',
			'class_view' => '?page=class_view&class=#CLASS_NAME#',
		//ms:dobrozhil.obj.class.view
			'class_properties' => '&view=properties',
			'class_methods_list' => '&view=methods&action=list',
			'class_objects_list' => '&view=objects&action=list',
			'class_export' => '&view=export',
			'class_export_full' => '&view=export_full',
		//ms:dobrozhil.obj.class.view.properties
			'class_property_add' => '&view=properties&action=add',
			'class_property_edit' => '&view=properties&action=edit&property=#PROPERTY_NAME#',
			'class_property_delete' => '&view=properties&action=delete&property=#PROPERTY_NAME#',

			'class_add_child' => '&view=add_child',
			'class_delete' => '&view=delete',
			'class_edit' => '&view=edit',
			'class_access' => '&view=access',
			'class_method_add' => '&view=methods&action=add',
			'class_method_edit' => '&view=methods&action=edit&method=#METHOD_NAME#',
			'class_object_add' => '&view=objects&action=add',
			'class_object_edit' => '&view=objects&action=edit&object=#OBJECT_NAME#'
	];

	protected function __construct ()
    {
        $panelPath = Application::getInstance()->getAppParam('panel_path');
        $this->arObjectsPagesDefaultSef['root_path'] = $panelPath . $this->arObjectsPagesDefaultSef['root_path'];
        $this->arObjectsPagesDefault['root_path'] = $panelPath . $this->arObjectsPagesDefault['root_path'];
    }

    public function getObjectsPagesDefault ($page, $bUseSef=true)
	{
		$page = strtolower($page);
		if ($bUseSef)
		{
			if (isset($this->arObjectsPagesDefaultSef[$page]))
			{
				return $this->arObjectsPagesDefaultSef[$page];
			}
		}
		else
		{
			if (isset($this->arObjectsPagesDefault[$page]))
			{
				return $this->arObjectsPagesDefault[$page];
			}
		}

		return null;
	}
}