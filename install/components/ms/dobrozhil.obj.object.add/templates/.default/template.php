<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj.object.add
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Components
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

use Ms\Core\Entity\Form;
use Ms\Core\Lib\Loc;

Loc::includeLocFile(__FILE__);

$arResult = &$this->arResult;

\Ms\Core\Entity\Application::getInstance()->includeComponent(
	'ms:core.form',
	'',
	array (
//		'Добавить новый объект',
		'FORM_SUBMIT_NAME' => Loc::getCompMess('ms:dobrozhil.obj.object.add','add_new_object'),
		'FORM_FIELDS' => array (
			new Form\Select(
//				'Выберите класс',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','select_class'),
//				'класс объекта определяет его свойства и методы',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','select_class_help'),
				'CLASS_NAME',
				null,
				true,
//				'---выбрать---',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','select_class_null'),
				$arResult['CLASSES_LIST'],
				null
			),
			new Form\InputText(
//				'Имя объекта',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','object_name'),
//				'имя объекта может состоять из заглавных и строчных латинских символов, цифр и знака подчеркивание',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','object_name_help'),
				'NAME',
				'',
				true,
				array('\Ms\Dobrozhil\Lib\Objects','checkObjectAddNameField')
			),
			new Form\InputText(
//				'Описание объекта',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','object_note'),
//				'краткое описание назначения класса',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','object_note_help'),
				'NOTE',
				'',
				false,
				null
			),
			new Form\Select(
//				'Выберите объект комнаты',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','room'),
//				'объект комнаты определяет местоположение нового объекта, если это необходимо',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','room_help'),
				'ROOM_NAME',
				null,
				false,
//				'---без комнаты---',
				Loc::getCompMess('ms:dobrozhil.obj.object.add','room_null'),
				$arResult['ROOMS_LIST'],
				null
			)
		)
	)
);

