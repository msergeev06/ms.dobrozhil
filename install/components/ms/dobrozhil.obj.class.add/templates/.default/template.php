<?php if(!defined('MS_PROLOG_INCLUDED')||MS_PROLOG_INCLUDED!==true)die('Access denied');
/**
 * Компонент модуля ms.dobrozhil ms:dobrozhil.obj.class.add
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
//	    'Добавить новый класс'
		'FORM_SUBMIT_NAME' => Loc::getCompMess('ms:dobrozhil.obj.class.add','add_new_class'),
		'FORM_FIELDS' => array (
			new Form\InputText(
//				'Имя класса',
				Loc::getCompMess('ms:dobrozhil.obj.class.add','class_name'),
//				'имя класса может состоять из заглавных и строчных латинских символов, цифр и знака подчеркивание',
				Loc::getCompMess('ms:dobrozhil.obj.class.add','class_name_help'),
				'NAME',
				'',
				true,
				array('\Ms\Dobrozhil\Lib\Classes','checkClassAddNameField')
			),
			new Form\InputText(
//				'Описание класса',
				Loc::getCompMess('ms:dobrozhil.obj.class.add','class_note'),
//				'краткое описание назначения класса',
				Loc::getCompMess('ms:dobrozhil.obj.class.add','class_note_help'),
				'NOTE',
				'',
				false,
				null
			),
			new Form\Select(
//				'Родительский класс',
				Loc::getCompMess('ms:dobrozhil.obj.class.add','parent_class'),
//				'создаваемый класс унаследует у родителя все свойства и методы',
				Loc::getCompMess('ms:dobrozhil.obj.class.add','parent_class_help'),
				'PARENT_CLASS',
				null,
				false,
//				'---без родителя---',
				Loc::getCompMess('ms:dobrozhil.obj.class.add','no_parent'),
				$arResult['CLASSES_LIST'],
				null
			)
		)
	)
);
?>
