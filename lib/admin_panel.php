<?php

namespace Ms\Dobrozhil\Lib;

class AdminPanel
{
	private static $arObjectsPagesDefaultSef = array(
		//ms:dobrozhil.obj
			'root_path' => '/admin/objects/',
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
			'class_methods' => 'methods/',
			'class_method_add' => 'add/',
			'class_method_edit' => 'edit/#METHOD_NAME#/',
			'class_objects' => 'objects/',
			'class_object_add' => 'add/',
			'class_object_edit' => 'edit/#OBJECT_NAME#/'
	);
	private static $arObjectsPagesDefault = array(
		//ms:dobrozhil.obj
			'root_path' => '/admin/objects/',
			'class_add' => '?page=class_add',
			'object_add' => '?page=object_add',
			'class_view' => '?page=class_view&class=#CLASS_NAME#',
		//ms:dobrozhil.obj.class.view
			'class_properties' => '?page=class_view&class=#CLASS_NAME#&view=properties',
			'class_methods_list' => '?page=class_view&class=#CLASS_NAME#&view=methods&action=list',
			'class_objects_list' => '?page=class_view&class=#CLASS_NAME#&view=objects&action=list',
			'class_export' => '?page=class_view&class=#CLASS_NAME#&view=export',
			'class_export_full' => '?page=class_view&class=#CLASS_NAME#&view=export_full',
		//ms:dobrozhil.obj.class.view.properties
			'class_property_add' => '?page=class_view&class=#CLASS_NAME#&view=properties&action=add',
			'class_property_edit' => '?page=class_view&class=#CLASS_NAME#&view=properties&action=edit&property=#PROPERTY_NAME#',
			'class_property_delete' => '?page=class_view&class=#CLASS_NAME#&view=properties&action=delete&property=#PROPERTY_NAME#',

			'class_add_child' => '?page=class_view&class=#CLASS_NAME#&view=add_child',
			'class_delete' => '?page=class_view&class=#CLASS_NAME#&view=delete',
			'class_edit' => '?page=class_view&class=#CLASS_NAME#&view=edit',
			'class_method_add' => '?page=class_view&class=#CLASS_NAME#&view=methods&action=add',
			'class_method_edit' => '?page=class_view&class=#CLASS_NAME#&view=methods&action=edit&method=#METHOD_NAME#',
			'class_object_add' => '?page=class_view&class=#CLASS_NAME#&view=objects&action=add',
			'class_object_edit' => '?page=class_view&class=#CLASS_NAME#&view=objects&action=edit&object=#OBJECT_NAME#'
	);

	public static function getObjectsPagesDefault ($page, $bUseSef=true)
	{
		$page = strtolower($page);
		if ($bUseSef)
		{
			if (isset(static::$arObjectsPagesDefaultSef[$page]))
			{
				return static::$arObjectsPagesDefaultSef[$page];
			}
		}
		else
		{
			if (isset(static::$arObjectsPagesDefault[$page]))
			{
				return static::$arObjectsPagesDefault[$page];
			}
		}

		return null;
	}
}