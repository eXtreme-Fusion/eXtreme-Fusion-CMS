<?php
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
*********************************************************/

// Interfejs dla klas zewnętrznych zabezpieczających formularze
interface Security_Intf
{
	// Zwraca tablicę z nazwami pól, które mają być zwrotnie przekazane do metody sprawdzającej odpowiedź
	public function getResponseInputs();

	// Sprawdza, czy podano prawidłową odpowiedź
	public function isValidAnswer($answer);

	// Wyświetla szablon zabezpieczenia
	public function getView();

	// Wyświetla szablon zabezpieczenia dla błędnej odpowiedzi
	public function getView_wrongAnswer();

	/**
	 * Przykład zastosowania dwóch powyższych metod:
	 *
		// Sprawdza, czy walidacja jest włączona
		if ($_sett->get('validation') == 1)
		{
			$_tpl->assign('security', isset($error['security']) ? $_protection->getView_wrongAnswer() : $_protection->getView());
		}
	 *
	 */

	// Służy do przekazania do klasy obiektów takich jak PDO czy Settings
	public function setObjects($_tpl);
}

// Interfejs dla klasy wewnętrznej obsługującej systemy zabezpieczeń
interface SecurityInternal_Intf
{
	/**
	 * Zwraca tablicę z nazwami, identyfikatorami oraz nazwami katalogów
	 * zainstalowanych modułów zabezpieczających formularze.
	 */
	public function getModulesData();

	/**
     * Zwraca referencje obiektu modułu,
	 * którego dane identyfikacyjne przesłano parametrami.
	 */
	public function getCurrentModule($module_name, $module_id);

	/**
	 * Zwraca tablicę danych z formularza pod indeksami w kolejności
	 * jak w tablicy z metody $_protection->getResponseInputs().
	 *
	 * Przykład użycia przy współpracy z metodą interfejsu modułu:
	 *
		if ( ! $_protection->isValidAnswer($_security->getUserAnswer($_protection->getResponseInputs())))
		{
			$error['security'] = TRUE;
		}
	 */
	public function getUserAnswer(array $inputs);
}

class Security
{
	protected $_pdo;
	protected $_request;

	public function __construct($_pdo, $_request)
	{
		$this->_pdo = $_pdo;
		$this->_request = $_request;
	}

	/**
	 * Tworzy tablicę danych, które moga posłużyć jako lista wyboru sposobu cochrony formularza.

	 **
	 ** Przykład użycia - by wyświetlić listę select z zaznaczoną wybraną opcją:
	 **
	  		Plik PHP:

				$_tpl->assign(
					'validation', $_security->getModulesData($_sett->getUnserialized('validation', 'register'))
				);

			Plik szablonu:

				<select name="validation">
					{section=validation}
						<option value="{$validation.id}"{if $validation.selected}selected="selected"{/if}>{$validation.name}</option>
					{/section}
				</select>
	 **
	 ** Przykład użycia - by zaktualizować ustawienie w bazie danych:
	 **
	  		Plik PHP:

				$_sett->update(array(
					'validation' => $_sett->serialize('validation', 'register', $_request->post('validation')->strip())
				));
	 *
	 */
	public function getModulesData($selected = NULL)
	{
		$return = array();

		if ($selected !== NULL)
		{
			$return[0] = array(
				'id' => '0',
				'name' => __('Without protection'),
				'selected' => '0' === $selected ? TRUE : NULL
			);
		}

		if ($data = $this->_pdo->getData('SELECT `id`, `folder`, `title` FROM [modules] WHERE `category` = \'security\''))
		{
			foreach($data as $value)
			{
				$return[] = array(
					'id' => $value['folder'],
					'name' => $value['title'],
					'selected' => $value['folder'] === $selected ? TRUE : NULL
				);
			}
		}

		return $return;
	}

	public function getUserAnswer(array $inputs)
	{
		$response = array();
		foreach($inputs as $field)
		{
			if ($this->_request->post($field, NULL)->show() !== NULL)
			{
				$response[] = $this->_request->post($field)->show();
			}
			else
			{
				$response[] = NULL;
			}
		}

		return $response;
	}

	public function getCurrentModule($folder)
	{
		if ($data = $this->_pdo->getData('SELECT `id` FROM [modules] WHERE `folder` = :folder', array(':folder', $folder, PDO::PARAM_STR)))
		{
			$path_class = DIR_MODULES.$folder.DS.'class'.DS.$folder.'.php';

			if (file_exists($path_class))
			{
				include $path_class;
				if (class_exists($folder, FALSE))
				{
					return new $folder;
				}
				else
				{
					throw new systemException('Klasa modułu zabezpieczającego nie istnieje!');
				}
			}

			throw new systemException('Plik z klasą modułu zabezpieczającego nie istnieje!');
		}

		return NULL;
	}

	public function checkModuleBeforeInstall($folder)
	{
		$path_class = DIR_MODULES.$folder.DS.'class'.DS.$folder.'.php';

		if (file_exists($path_class))
		{
			include $path_class;
			if (class_exists($folder, FALSE))
			{
				return TRUE;
			}
			else
			{
				throw new systemException('Klasa modułu <span class="bold">'.$folder.'</span> nie istnieje.');
			}
		}

		throw new systemException('Plik z klasą <span class="bold">'.$folder.'</span> nie istnieje pod wymaganą lokalizacją.');
	}
}