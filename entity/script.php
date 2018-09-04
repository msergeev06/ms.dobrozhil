<?php

namespace Ms\Dobrozhil\Entity;

use Ms\Core\Entity\ErrorCollection;
use Ms\Core\Entity\Type\Date;
use Ms\Dobrozhil\Lib\Scripts;
use Ms\Core\Lib\Logs;
use Ms\Core\Lib\Loc;
use Ms\Core\Lib\Loader;
use Ms\Dobrozhil\Interfaces\CodeEditor;
use Ms\Dobrozhil\Tables\ScriptsTable;

Loc::includeLocFile(__FILE__);

class Script
{
	/**
	 * @var string
	 */
	private $name = null;

	/**
	 * @var string
	 */
	private $module = null;

	/**
	 * @var string
	 */
	private $class = null;

	/**
	 * @var CodeEditor
	 */
	private $codeEditor = null;

	/**
	 * @var string
	 */
	private $code = null;

	/**
	 * @var int
	 */
	private $category = 0;

	/**
	 * @var bool
	 */
	private $bError = false;

	/**
	 * @var null|ErrorCollection
	 */
	private $errorCollection = null;

	/**
	 * Script constructor.
	 *
	 * @param string $sScriptName Уникальное имя скрипта
	 */
	public function __construct ($sScriptName)
	{
		$this->bError = false;
		if (!$arScript = Scripts::getScriptDb($sScriptName))
		{
			$this->bError = true;
			Logs::setError(
			//'Скрипт #SCRIPT_NAME# не найден'
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_no_script',
					array ('SCRIPT_NAME'=>$sScriptName)
				),
				array ('ERROR_CODE'=>'SCRIPT_NOT_EXISTS'),
				$this->errorCollection
			);
		}
		else
		{
			if (is_null($arScript['MODULE']) || is_null($arScript['CLASS']))
			{
				$arScript['MODULE'] = 'ms.dobrozhil';
				$arScript['CLASS'] = '\Ms\Dobrozhil\Entity\Code\TextEditor';
			}
			if (!Loader::issetModule($arScript['MODULE']) || !Loader::includeModule($arScript['MODULE']))
			{
				$this->bError = true;
				Logs::setError(
				//'Ошибка инициализации скрипта #SCRIPT_NAME#. Требуемый модуль #MODULE_NAME# не установлен, либо возникла ошибка при подключении'
					Loc::getModuleMessage(
						'ms.dobrozhil',
						'error_run_script_module',
						array ('SCRIPT_NAME'=>$sScriptName,'MODULE_NAME'=>$arScript['MODULE'])
					),
					array ('ERROR_CODE'=>'SCRIPT_ERROR_MODULE'),
					$this->errorCollection
				);
			}
			elseif (!Loader::classExists($arScript['CLASS']))
			{
				$this->bError = true;
				Logs::setError(
				//'Ошибка инициализации скрипта #SCRIPT_NAME#. Класс (#CLASS_NAME#) модуля "#MODULE_NAME#" не найден'
					Loc::getModuleMessage(
						'ms.dobrozhil',
						'error_script_class',
						array ('SCRIPT_NAME'=>$sScriptName,'MODULE_NAME'=>$arScript['MODULE'],'CLASS_NAME'=>$arScript['CLASS'])
					),
					array ('ERROR_CODE'=>'CLASS_NOT_EXISTS'),
					$this->errorCollection
				);
			}
			elseif (!($arScript['CLASS'] instanceof CodeEditor))
			{
				$this->bError = true;
				Logs::setError(
				//'Ошибка инициализации скрипта #SCRIPT_NAME#. Описанный класс (#CLASS_NAME#) модуля "#MODULE_NAME#" не реализует интерфейс редактора кода'
					Loc::getModuleMessage(
						'ms.dobrozhil',
						'error_script_interface',
						array ('SCRIPT_NAME'=>$sScriptName,'MODULE_NAME'=>$arScript['MODULE'],'CLASS_NAME'=>$arScript['CLASS'])
					),
					array (),
					$this->errorCollection
				);
			}
			else
			{
				$this->name = $sScriptName;
				$this->module = $arScript['MODULE'];
				$this->class = $arScript['CLASS'];
				if ((int)$arScript['CATEGORY_ID']>0)
				{
					$this->category = (int)$arScript['CATEGORY_ID'];
				}
				$this->codeEditor = new $this->class();
				$this->code = $this->codeEditor->getCode($this->name);
				if ($this->code===false)
				{
					$this->bError = true;
					Logs::setError(
					//'Ошибка инициализации скрипта #SCRIPT_NAME#. Код не был получен из редактора'
						Loc::getModuleMessage(
							'ms.dobrozhil',
							'error_script_code',
							array ('SCRIPT_NAME'=>$sScriptName)
						),
						array ('ERROR_CODE'=>'SCRIPT_CODE_EDITOR'),
						$this->errorCollection
					);
				}
			}
		}
	}

	/**
	 * Возвращает TRUE, если по время выполнения предыдущей операции произошла ошибка
	 *
	 * @return bool
	 */
	public function isError()
	{
		return $this->bError;
	}

	/**
	 * Возвращает код скрипта
	 *
	 * @return string
	 */
	public function getCode ()
	{
		if (!$this->bError)
		{
			return $this->code;
		}

		return '';
	}

	/**
	 * Сохраняет изменения в коде скрипта
	 *
	 * @param string $code Код скрипта
	 *
	 * @return bool
	 */
	public function saveCode ($code=null)
	{
		if (is_null($code))
		{
			$code = $this->code;
		}

		return $this->codeEditor->saveCode($this->name,$code);
	}

	/**
	 * Возвращает ID категории скрипта
	 *
	 * @return int
	 */
	public function getCategoryId ()
	{
		return $this->category;
	}

	/**
	 * Выполняет скрипт
	 *
	 * @param array $arParams Массив параметров
	 *
	 * @return mixed|null
	 */
	public function run ($arParams = array())
	{
		$this->bError = false;
		try
		{
			$result = eval($this->code);
			return $result;
		}
		catch (\ParseError $p)
		{
			$this->bError = true;
			Logs::setError(
			//'Ошибка синтаксиса скрипта #SCRIPT_NAME#: $MESSAGE#'
				Loc::getModuleMessage(
					'ms.dobrozhil',
					'error_script_syntax',
					array ('SCRIPT_NAME'=>$this->name,'MESSAGE'=>$p->getMessage())
				),
				array ('ERROR_CODE'=>'SCRIPT_CODE_SYNTAX'),
				$this->errorCollection
			);
		}

		return NULL;
	}

	/**
	 * Устанавливает время и параметры последнего запуска скрипта
	 *
	 * @param array     $arParams Массив параметров
	 * @param Date|NULL $date     Дата (если не установлена - текущая дата)
	 */
	public function setLastRun ($arParams = array(), Date $date=null)
	{
		if (is_null($date))
		{
			$date = new Date();
		}
		$arUpdate = array(
			'LAST_PARAMETERS' => $arParams,
			'LAST_RUN' => $date
		);

		ScriptsTable::update($this->name,$arUpdate);
	}

	/**
	 * Возвращает объект редактора скрипта
	 *
	 * @return CodeEditor
	 */
	public function getEditor()
	{
		return $this->codeEditor;
	}
}