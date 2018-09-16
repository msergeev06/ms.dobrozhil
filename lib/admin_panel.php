<?php

namespace Ms\Dobrozhil\Lib;

class AdminPanel
{
	private static $arObjectsPagesDefaultSef = array(
		'root_path' => '/admin/objects/',
		'class_add' => 'class_add/',
		'object_add' => 'object_add/',
		'class_add_child' => 'class_add_child/#CLASS_NAME#/',
		'class_delete' => 'class_delete/#CLASS_NAME#/',
		'class_edit' => 'class_edit/#CLASS_NAME#/',
		'class_method_add' => 'class_method_add/#CLASS_NAME#/',
		'class_method_edit' => 'class_method_edit/#CLASS_NAME#/#METHOD_NAME#/',
		'class_methods_list' => 'class_methods_list/#CLASS_NAME#/',
		'class_object_add' => 'object_add/#CLASS_NAME#/',
		'class_object_edit' => 'object_edit/#OBJECT_NAME#/',
		'class_objects_list' => 'class_objects_list/#CLASS_NAME#/',
		'class_properties_list' => 'class_properties_list/#CLASS_NAME#/',
		'class_property_add' => 'class_property_add/#CLASS_NAME#/',
		'class_property_edit' => 'class_property_edit/#CLASS_NAME#/#PROPERTY_NAME#/'
	);
	private static $arObjectsPagesDefault = array(
		'root_path' => '/admin/objects/',
		'class_add' => '?page=class_add',
		'object_add' => '?page=object_add',
		'class_add_child' => '?page=class_add_child&class=#CLASS_NAME#',
		'class_delete' => '?page=class_delete&class=#CLASS_NAME#',
		'class_edit' => '?page=class_edit&class=#CLASS_NAME#',
		'class_method_add' => '?page=class_method_add&class=#CLASS_NAME#',
		'class_method_edit' => '?page=class_method_edit&class=#CLASS_NAME#&method=#METHOD_NAME#',
		'class_methods_list' => '?page=class_methods_list&class=#CLASS_NAME#',
		'class_object_add' => '?page=object_add&class=#CLASS_NAME#',
		'class_object_edit' => '?page=object_edit&object=#OBJECT_NAME#',
		'class_objects_list' => '?page=class_objects_list&class=#CLASS_NAME#',
		'class_properties_list' => '?page=class_properties_list&class=#CLASS_NAME#',
		'class_property_add' => '?page=class_property_add&class=#CLASS_NAME#',
		'class_property_edit' => '?page=class_property_edit&class=#CLASS_NAME#&property=#PROPERTY_NAME#'
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