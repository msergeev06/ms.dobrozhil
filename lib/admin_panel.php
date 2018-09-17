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
			'class_properties_list' => 'properties_list/',
			'class_methods_list' => 'methods_list/',
			'class_objects_list' => 'objects_list/',
			'class_export' => 'export/',
			'class_export_full' => 'export_full/',

			'class_add_child' => 'add_child/',
			'class_delete' => 'delete/',
			'class_edit' => 'edit/',
			'class_method_add' => 'method_add/',
			'class_method_edit' => 'method/#METHOD_NAME#/edit/',
			'class_object_add' => 'object_add/',
			'class_object_edit' => 'object/#OBJECT_NAME#/edit/',
			'class_property_add' => 'property_add/',
			'class_property_edit' => 'property/#PROPERTY_NAME#/edit/'
	);
	private static $arObjectsPagesDefault = array(
		//ms:dobrozhil.obj
			'root_path' => '/admin/objects/',
			'class_add' => '?page=class_add',
			'object_add' => '?page=object_add',
			'class_view' => '?page=class_view&class=#CLASS_NAME#',
		//ms:dobrozhil.obj.class.view
			'class_properties_list' => '?page=class_view&class=#CLASS_NAME#&view=properties_list',
			'class_methods_list' => '?page=class_view&class=#CLASS_NAME#&view=methods_list',
			'class_objects_list' => '?page=class_view&class=#CLASS_NAME#&view=objects_list',
			'class_export' => '?page=class_view&class=#CLASS_NAME#&view=export',
			'class_export_full' => '?page=class_view&class=#CLASS_NAME#&view=export_full',

			'class_add_child' => '?page=class_view&class=#CLASS_NAME#&view=add_child',
			'class_delete' => '?page=class_view&class=#CLASS_NAME#&view=delete',
			'class_edit' => '?page=class_view&class=#CLASS_NAME#&view=edit',
			'class_method_add' => '?page=class_view&class=#CLASS_NAME#&view=method_add',
			'class_method_edit' => '?page=class_view&class=#CLASS_NAME#&view=method_edit&method=#METHOD_NAME#',
			'class_object_add' => '?page=class_view&class=#CLASS_NAME#&view=object_add',
			'class_object_edit' => '?page=class_view&class=#CLASS_NAME#&view=object_edit&object=#OBJECT_NAME#',
			'class_property_add' => '?page=class_view&class=#CLASS_NAME#&view=property_add',
			'class_property_edit' => '?page=class_view&class=#CLASS_NAME#&view=property_edit&property=#PROPERTY_NAME#'
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