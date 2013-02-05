<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This product is licensed under the BSD License.
| http://extreme-fusion.org/ef5/license/
***********************************************************/

/**
 * Function to hashing in `sha512` algorithm.
 *
 * @param   string  value to hash
 * @return  string
 */
function sha512($value)
{
	return hash('sha512', $value);
}

class User {

	// Tablica zawierająca dane zalogowanego użytkownika
	protected $_data = array();

	// Czy użytkownik jest zalogowany?
	protected $_logged = FALSE;

	// Zawiera tzw. "sól" do kodowania haseł użytkowników
	protected $_salt = NULL;

	// Klasa zawierająca ustawienia systemu
	protected $_sett;

	// Przechowuje obiekt bazy danych
	protected $_pdo;

	// Przechowuje adres IP użytkownika
	protected $_ip;
	
	protected $_custom_data;

	/**
	 * Czas ważności ciasteczka.
	 * Wartość tej zmiennej jest trzecim paramtrem funkcji setcookie();

	 * !To nie to samo co czas utrzymywania zalogowania.
	 * !Za czas utrzymywania zalogowania odpowiada
	 * !ustawienie "user_logged_lifetime", którego wartość
	 * !nie powinna być większa niż ta z poniższej zmiennej.
	 */
	protected $_cookie_life_time;

	protected
		$_all_roles 	 = array(),		// Wszystkie grupy uprawnień
		$_roles 		 = array(),		// Grupy, do których należy zalogowany użytkownik
		$_perms			 = array(),		// Wszystkie uprawnienia
		$_perms_sections = array();

	// Indeksy systemowych grup uprawnień
	public $groups = array(1, 2, 3);

	// Dane z tabeli `users_online`
	protected $_online = array();

	/**
	 * Czas od ostatniej aktywności, by móc uznać użytkownika za będącego online.
	 *
	 * Od tego ustawienia zależna jest też tolerencja sesji zalogowania.
	 * Jeżeli czas utrzymywania sesji minął, ale użytkownik wykazuje aktywność
	 * w zakresie czasowym przypisanym do tej zmiennej, to nie zostanie wylogowany.
	 */
	protected $_online_activity;

	// Cache użytkowników online
	protected $_get_users_online;

	// Cache gości online
	protected $_get_guests_online = NULL;

	// Lista ID rekordów do usunięcia jako duplikaty // deprecated
	//protected $_online_to_remove = array();

	/**
	 * Przypisuje zmiennym referencje do obiektów klas zewnętrznych
	 *
	 * @param   Sett	  klasa ustawień systemu
	 * @param   Database  klasa bazy danych
	 * @return  void
	 */
	public function __construct(Sett $sett, Data $pdo)
	{
		$this->_sett = $sett;
		$this->_pdo = $pdo;
		$this->setAllRolesCache();
		$this->setPermissionsCache();
		$this->setPermissionsSectionsCache();
		$this->_try_login = FALSE;

		$this->_online_activity = time()-$sett->getUns('loging', 'user_active_time');

		// Przed edycją należy przeczytać opis przy deklaracji zmiennej
		$this->_cookie_life_time = time() + 60*60*24*31;

		if (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP))
		{
			$this->_ip = $_SERVER['REMOTE_ADDR'];
		}
		else
		{
			throw new systemException('Twój adres IP: '.$_SERVER['REMOTE_ADDR'].' jest nie prawidłowy!!!');
		}

	}

	public function __destruct()
	{
		// TODO: do utawień dodać czas ostatniego czyszczenia tabeli users_online by nie czyściło za każdym przeładowaniem strony
		$this->onlineCleanTable();
	}


	/************* metody do testów */

	public function getLang()
	{
		if ($this->isLoggedIn())
		{
			return $this->get('lang');
		}

		return System::detectBrowserLanguage(TRUE);

	}
	// Sprawdzanie dostępności nazwy użytkownika i ewentualna zmiana
	// aby zmienić nazwę usera, nalezy podać ID
	// Przed wywołaniem funkcji należy sprawdzić nazwę użytkownika, np:
	// if ($username != $_user->getByID($_request->get('User')->show(), 'username'))
	public function newName($username, $id = NULL)
	{
		if ($this->isValidLogin($username))
		{
			$row = $this->_pdo->getRow('SELECT `username` FROM [users] WHERE `username` = :username', array(':username', $username, PDO::PARAM_STR));
			if ( ! $row)
			{
				if ($id !== NULL)
				{
					return $this->update(array('username' => $username), $id);
				}
				return TRUE;
			}
			return FALSE;
		}

		throw new userException(__('Entered the wrong data type.'));
	}

	// Sprawdzanie dostępności adresu e-mail i ewentualna zmiana
	// Przed wywołaniem funkcji należy sprawdzić adres email, np:
	// if ($_request->post('email') != $_user->getByID($_request->get('User')->show(), 'email'))
	public function newEmail($email, $id = NULL)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$row = $this->_pdo->getRow('SELECT `email` FROM [users] WHERE `email`= :email', array(':email', $email, PDO::PARAM_STR));
			if ( ! $row)
			{
				if ($id !== NULL)
				{
					return $this->update(array('email' => $email), $id);
				}
				return TRUE;
			}
			return FALSE;
		}

		throw new userException(__('Entered the wrong data type.'));
	}

	public function emailExists($email)
	{
		return (bool) $this->_pdo->getRow('SELECT `email` FROM [users] WHERE `email`= :email', array(':email', $email, PDO::PARAM_STR));
	}


	// Aktualizuje hasło ze sprawdzeniem starego lub nie - w zależności od argumentu metody
	public function changePass($id, $new_pass, $old_pass = NULL, $con_new_pass = NULL)
	{
		if (isNum($id))
		{
			if ($old_pass === NULL)
			{
				return $this->update(array('password' => $this->createUserHash($id, $new_pass)), $id);
			}
			else
			{
				if ($this->checkNewPass($id, $new_pass, $old_pass, $con_new_pass))
				{
					return $this->update(array('password' => $this->createUserHash($id, $new_pass)), $id);
				}
			}
		}

		return FALSE;
	}

	// Sprawdza zarówno zgodność podanego hasła z aktualnie ustawionym na danym koncie oraz czy prawidłowo powtórzono nowe
	protected function checkNewPass($id, $new_pass, $old_pass, $con_new_pass)
	{
		if (isNum($id) && $new_pass && $new_pass === $con_new_pass)
		{
			return $this->checkOldPass($id, $old_pass);
		}

		return FALSE;
	}

	// Sprawdza tylko zgodność podanego hasła z aktualnie ustawionym na danym koncie
	public function checkOldPass($id, $pass)
	{
		if (isNum($id))
		{
			return $this->createUserHash($id, $pass) === $this->getByID($id, 'password');
		}

		return FALSE;
	}

	// Aktualizuje hash w tablicy sesji zalogowania użytkownika
	public function updateLoginSession($pass)
	{
		if (isset($_SESSION['user']))
		{
			$_SESSION['user']['hash'] = substr($this->createUserHash($this->get('id'), $pass), 0, 5);
		}

		if (isset($_COOKIE['user']))
		{
			HELP::setCookie('user', $this->get('id').'.'.substr($this->createUserHash($this->get('id'), $pass), 0, 5));
		}
	}



	//Przykład:
	//$file = $_FILES['avatar'];
	// Trzeci parametr ustawiony na TRUE sprawia, że istniejący na serwerze avatar nie może zostać nadpisany przez jego własciciela.
	public function saveNewAvatar($id, $file, $check_exists = FALSE)
	{
		if ($file && is_uploaded_file($file['tmp_name']))
		{
			// Zamiana znaków na małe w nazwie
			$file['name'] = strtolower($file['name']);

			$ext = substr(strstr($file['name'], '.'), 1);
			$name = strstr($file['name'], '.', TRUE); // As of PHP 5.3.0
			$info = getimagesize($file['tmp_name']);

			if ($info)
			{
				if ($file['size'] <= $this->_sett->get('avatar_filesize'))
				{
					if ($info[0] <= $this->_sett->get('avatar_width') && $info[1] <= $this->_sett->get('avatar_height'))
					{
						if (in_array($ext, $extensions_allowed = array('gif', 'jpg', 'jpeg', 'png')))
						{
							// Nowa nazwa pliku
							$new_name = str_replace(' ', '-', $name).'['.$id.'].'.$ext;
							// Nowa nazwa pliku wraz ze ścieżką
							$new_path_name = DIR_IMAGES.'avatars'.DS.$new_name;

							if (! file_exists($new_path_name) || ! $check_exists)
							{
								if (move_uploaded_file($file['tmp_name'], $new_path_name))
								{
									return $this->updateAvatar($new_name, $id);
								}

								unlink($file['tmp_name']);
								return FALSE;
							}
							else
							{
								throw new userException('Plik o podanej nazwie już istnieje.');
							}
						}
						else
						{
							throw new userException('You must upload file with one of the following extensions: :extensions', array(':extensions' => implode(', ', $extensions_allowed)));
						}
					}
					else
					{
						throw new userException('Rozmiary obrazka są nieprawidłowe. Dopuszczalna szerokość: :width, wysokość: :height', array(':width' => $this->_sett->get('avatar_width'), ':height' => $this->_sett->get('avatar_height')));
					}
				}
				else
				{
					throw new userException('The uploaded image has got to large file size.');
				}
			}
			else
			{
				throw new userException('The uploaded file seems not to be an image.');
			}
		}
	}

	// Aktualizuje avatar użytkownika w bazie.
	protected function updateAvatar($name, $id)
	{
		if (isNum($id))
		{
			return (bool) $this->_pdo->exec('UPDATE [users] SET avatar = :avatar WHERE id = '.$id, array(':avatar', $name, PDO::PARAM_STR));
		}

		return FALSE;
	}

	// Usuwa avatar w bazie i na serwerze.
	// Nazwy pozyskuje z bazy - jest to zabezpieczenie:
	// gdyby nazwa pochodziła z formularza, istniałoby zagrożenie, że ktoś bedzie próbował usunąć plik, do którego nie ma prawa
	// poprzez zamieszczenie w atrybucie value znaków `../`.
	public function delAvatar($id = NULL)
	{
		if ($id === NULL) // Usuwa własny avatar
		{
			if (file_exists(DIR_IMAGES.'avatars'.DS.$this->get('avatar')))
			{
				unlink(DIR_IMAGES.'avatars'.DS.$avatar);
			}

			return $this->updateAvatar('', $this->get('id'));
		}
		elseif (isNum($id)) // Usuwa avatar użytkownika o podanym ID
		{
			$avatar = $this->getByID($id, 'avatar');

			if (file_exists(DIR_IMAGES.'avatars'.DS.$avatar))
			{
				unlink(DIR_IMAGES.'avatars'.DS.$avatar);
			}

			return $this->updateAvatar('', $id);
		}

		return FALSE;
	}

	// Usuwa wszystkie avatary na serwerze i w bazie danych
	public function delAllAvatars()
	{
		$files = scandir(IMAGES.'avatars'.DS);

		foreach ($files as $val)
		{
			if ($val != '.' && $val != '..')
			{
				unlink(IMAGES.'avatars'.DS.$val);
			}
		}

		return $this->update(array('avatar' => ''), FALSE);
	}

	//Aktualizuje dane pól użytkowników
	public function updateField($field, $id = NULL, $index_val = NULL, $field_val = NULL)
	{
		echo 'depr';
		if($id === NULL)
		{
			$this->_pdo->exec('UPDATE [users_data] SET '.$field);
		}
		else
		{
			$this->_pdo->exec('INSERT INTO [users_data] (`user_id`, '.$index_val.') VALUES ('.$id.', '.$field_val.') ON DUPLICATE KEY UPDATE '.$field.'');
		}
		return TRUE;
	}

	// Setter klasy Users
	// To co tu trafia musi być wcześniej przefiltrowane,
	// gdyż w metodzie nie ma bindowania PDO!!
	public function update(array $data, $id = NULL)
	{
		foreach($data as $key => $val)
		{
			$temp[] = $key." = '".$val."'";
		}

		$update = implode(', ', $temp);



		if ($id === FALSE) // Aktualizacja wiersza wszystkich użytkowników
		{
			return $this->_pdo->exec('UPDATE [users] SET '.$update.', lastupdate = '.time());
		}
		elseif ($id === NULL) // Aktualizacja własnego wiersza
		{
			return $this->_pdo->exec('UPDATE [users] SET '.$update.', lastupdate = '.time().' WHERE `id` = '.$this->get('id'));
		}
		elseif (isNum($id)) // Aktualizacja wiersza użytkownika o podanym ID
		{
			return $this->_pdo->exec('UPDATE [users] SET '.$update.', lastupdate = '.time().' WHERE `id` = '.$id);
		}

		return FALSE;
	}
	/************koniec wersji do testów */

	/**********************************/
	/******* 	AUTORYZACJA		*******/
	/**********************************/

	/**
	 * Sprawdza poprawność nazwy użytkownika.
	 *
	 * Przykładowo może być używane do walidacji logowania
	 * lub przy rejestracji nowego konta.
	 */
	protected function isValidLogin($username)
	{
		return (! is_array($username) ? (! preg_match("#[^\w\d-]+#i", $username) ? TRUE : FALSE) : FALSE);
	}

	/**
	 * Zwraca hash przeznaczony dla pól `user_hash`
	 * oraz `admin_hash`, które odpowiadają za autoryzację.
	 * Wartość z tych pól porównywana jest z zawartością sesji
	 * bądź ciasteczka zalogowania.
	 */
	private function createLoginHash()
	{
		$data = array('!', '@', '#', '$', '^');
		return substr(md5(microtime(TRUE).$data[rand(0, 4)]), 0, 8);
	}
	/**
	 * Loguje użytkownika, o ile podał poprawne dane.
	 *
	 *     // Zwraca wartość prawda / fałsz, w zależności od poprawności logowania
	 *    $user->userLogin($_POST['username'], $_POST['password']));
	 *
	 * OPIS DZIAŁANIA AUTORYZACJI
	 * Przy podaniu poprawnych danych logowania tworzy się zarówno sesja, jak i ciasteczko.
	 * W przypadku wygaśnięcie ciasteczka, użytkownik bedzie tak długo zalogowany, aż
	 * jego sesja wygaśnie, a nie od razu po wygaśnięciu ciasteczka (co powodowało wylogowanie)
	 * podczas przeglądania strony.
	 *
	 * @param   string  nazwa użytkownika
	 * @param   string  hasło użytkownika
	 * @return  bool
	 */
	public function userLogin($username, $pass, $remember = FALSE)
	{
		$username = trim($username);

		if ($this->isValidLogin($username))
		{
			if ($query = $this->_pdo->getRow("SELECT `id`, `status`, `salt`, `password` FROM [users] WHERE `username`='{$username}' AND status = 0 LIMIT 1"))
			{
				$sha512 = sha512($query['salt'].'^'.$pass);

				// Sprawdzanie poprawności hasła
				if ($sha512 === $query['password'])
				{
					$this->_data = $query;

					$hash = $this->createLoginHash();
					$time = time();

					// Zapis sesji
					$_SESSION['user'] = array(
						'id'   => $this->_data['id'],
						'hash' => $hash
					);

					// Tworzenie ciasteczka
					HELP::setCookie('user', $query['id'].'.'.$hash, $this->_cookie_life_time);

					// Aktualizacja użytkowników online
					$this->setUserOnline();

					$_crypt = new Crypt(CRYPT_KEY, CRYPT_IV);

					// Rzutowanie danych by móc zapisać w odpowiedniej postaci
					$remember = (int) $remember;

					// Aktualizacja danych autoryzacji w bazie danych
					$this->_pdo->exec('UPDATE [users] SET `user_hash` = \''.$hash.'\', `user_last_logged_in` = '.time().', `user_remember_me` = '.$remember.', `browser_info` = \''.$_crypt->encrypt(md5($_SERVER['HTTP_USER_AGENT'])).'\' WHERE `id` = '.$query['id']);

					return TRUE;
				}
			}
		}

		return FALSE;
	}

	// Sprawdza poprawność danych logowania
	public function checkLoginData($username, $pass)
	{
		$username = trim($username);

		if ($this->isValidLogin($username))
		{
			if ($query = $this->_pdo->getRow("SELECT `salt` AS salt FROM [users] WHERE `username`='{$username}'"))
			{
				$sha512 = sha512($query['salt'].'^'.$pass);

				if ($row = $this->_pdo->getRow("SELECT `id` FROM [users] WHERE `username`='{$username}' AND `password`='{$sha512}' AND `status`=0"))
				{
					return $row['id'];
				}
			}
		}

		return FALSE;
	}

	/**
	 * Podtrzymuje sesję logowania dla użytkownika
	 *
	 * @param   integer  ID użytkownika
	 * @param   string
	 * @return  bool
	 */
	public function userLoggedInBySession($id, $hash)
	{
		// Fix for IE for "o" value of $id; todo: sprawdzić czemu trafiało tam "o" po dłuższej nieobecności na stronie
		if (isNum($id, FALSE, FALSE))
		{
			if ($row = $this->_pdo->getRow('SELECT u.*, ud.* FROM [users] u LEFT JOIN [users_data] ud ON u.`id`= ud.`user_id` WHERE u.`id` = '.$id))
			{
				//echo 'db: '.$row['user_hash'].'<br />'.$hash; exit;
				if ($row['user_hash'] === $hash)
				{
					$_crypt = new Crypt(CRYPT_KEY, CRYPT_IV);

					if ($_crypt->decrypt($row['browser_info']) === md5($_SERVER['HTTP_USER_AGENT']))
					{
						// Obliczanie wyjściowego czasu, by móc ocenić, czy sesja jest prawidłowa.
						if ($row['user_remember_me'])
						{
							$session_start_time = time()-$this->_sett->getUns('loging', 'site_remember_loging_time');
						}
						else
						{
							$session_start_time = time()-$this->_sett->getUns('loging', 'site_normal_loging_time');
						}

						if ($row['user_last_logged_in'] > $session_start_time || $row['lastvisit'] > $this->_online_activity)
						{
							$hash = $this->createLoginHash();

							$_SESSION['user']['hash'] = $hash;

							// Aktualizacja danych autoryzacji w bazie danych
							$this->_pdo->exec('UPDATE [users] SET `user_hash` = \''.$hash.'\' WHERE `id` = '.$row['id']);

							// Aktualizacja ciasteczka
							HELP::setCookie('user', $row['id'].'.'.$hash, $this->_cookie_life_time);

							$this->_data = $row;
							$this->_logged = TRUE;

							return TRUE;
						}
						// Czas żywotności sesji zalogowania minął, a użytkownik był nieaktywny przez ponad 4 minuty
						else
						{
							// Wylogowywanie
							//HELP::removeSession('user');
							HELP::removeCookie('user');
							$this->setGuestOnline();
						}
					}
				}
			}
		}

		return FALSE;
	}

	/**
	 * Podtrzymuje ciasteczko zalogowania użytkownika
	 *
	 * @param   integer  ID użytkownika
	 * @param   string
	 * @return  bool
	 */
	public function userLoggedInByCookie($cookie)
	{
		if ($data = explode('.', $cookie))
		{
			if (isNum($data[0]))
			{
				if ($row = $this->_pdo->getRow('SELECT u.*, ud.* FROM [users] u LEFT JOIN [users_data] ud ON u.`id`= ud.`user_id` WHERE u.`id` = '.$data[0]))
				{
					if ($row['user_hash'] === $data[1])
					{
						// Obliczanie wyjściowego czasu, by móc ocenić, czy sesja jest prawidłowa.
						if ($row['user_remember_me'])
						{
							$session_start_time = time()-60*60*24*21;
						}
						else
						{
							$session_start_time = time()-60*60*12;
						}

						if ($row['user_last_logged_in'] > $session_start_time)
						{
							$hash = $this->createLoginHash();

							// Aktualizacja danych autoryzacji w bazie danych
							$this->_pdo->exec('UPDATE [users] SET `user_hash` = \''.$hash.'\' WHERE `id` = '.$row['id']);

							// Odnawiamy sesje
							$_SESSION['user'] = array(
								'id'   => $row['id'],
								'hash' => $hash
							);

							// Aktualizacja ciasteczka
							HELP::setCookie('user', $row['id'].'.'.$hash, $this->_cookie_life_time);

							$this->_data = $row;
							$this->_logged = TRUE;

							return TRUE;
						}
						else
						{
							// Sesja juz nie istnieje, więc odwiedzenie strony nastąpiło po zamknięciu
							// i ponownym otworzeniu przeglądarki.
							// Ciasteczko jest już przestarzałe, więc nastąpi wylogowanie.

							HELP::removeSession('user');
							HELP::removeCookie('user');
							$this->setGuestOnline();
						}
					}
				}
			}
		}

		return FALSE;
	}

	public function getUsername($user = NULL, $format = NULL)
	{
		if ($user === NULL)
		{
			$username = $this->get('username');
			$role = $this->get('role');
		}
		else
		{
			$username = $this->getByID($user, 'username');
			$role = $this->getByID($user, 'role');
		}

		if ($format === NULL)
		{
			$role = $this->_pdo->getRow('SELECT `format` FROM [groups] WHERE `id` = :role', array(
				array(':role', $role, PDO::PARAM_STR)
			));
		}

		if ($role)
		{
			return str_replace('{username}', $username, $role['format']);
		}

		return $username;
	}

	/**
	 * Zwraca ścieżkę do avataru użytkownika lub obrazków zastępczych jeśli avataru brak.
	 *
	 * @param   integer  ID użytkownika
	 * @param   string   domyślny avatar systemowy
	 * @return  string
	 */
	public function getAvatarAddr($user = NULL, $default = "none.gif")
	{
		$dir = ADDR_IMAGES.'avatars/';
		if ($user === NULL)
		{
			$avatar_file = $this->get('avatar');
		}
		else
		{
			$avatar_file = $this->getByID($user, 'avatar');
		}

		if ($avatar_file && file_exists(DIR_IMAGES.'avatars'.DS.$avatar_file))
		{
			return $dir.$avatar_file;
		}
		elseif (file_exists(DIR_IMAGES.'avatars'.DS.$default))
		{
			return $dir.$default;
		}
		else
		{
			return ADDR_IMAGES.'loading.gif';
		}
	}

	/**
	 * Tworzy hash z użyciem soli użytkownika o danym indentyfikatorze
	 *
	 * @param   integer  ID użytkownika
	 * @param   string   tekst do zakodowania
	 * @return  string
	 */
	public function createUserHash($id, $pass)
	{
		if ( ! $data = $this->get('salt', $id))
		{
			return FALSE;
		}
		return sha512($data.'^'.$pass);
	}

	/**
	 * Tworzy hash dla ciągu znaków. Dobre dla użytkowników
	 * nieposiadających konta, którzy się właśnie rejestrują.
	 *
	 * @param   string  tekst do zakodowania
	 * @return  string
	 */
	protected function createHash($val)
	{
		$this->_salt = substr(sha512(uniqid(rand(), true)), 0, 5);
		return sha512($this->_salt.'^'.$val);
	}

	/**
	 * Zwraca zakdowany ciąg znaków utworzony wcześniej
	 * przez metodę `getSalt`.
	 *
	 * @return  string
	 */
	public function getSalt()
	{
		return $this->_salt;
	}

	/**
	 * Metoda zwracająca element z tablicy o indeksie podanym przez parametr
	 * bądź całą tablicę, jeżeli parametr nie istnieje.
	 *
	 * @param   string  indeks tablicy
	 * @return  mixed
	 */
	public function get($key = NULL)
	{
		if ($key === NULL)
		{
			return $this->_data;
		}
		else
		{
			$key = str_replace('user_', '', $key);
			if (isset($this->_data[$key]))
			{
				return $this->_data[$key];
			}
		}
		return FALSE;
	}

	/**
	 * Metoda zwracająca dane użytkownika o danym identyfikatorze
	 * w zależności od podanego klucza
	 *
	 * @param   integer  ID użytkownika
	 * @param   string   indeks tablicy
	 * @return  mixed
	 */
	final public function getByID($id, $key = NULL)
	{
		if (isNum($id))
		{
			$data = $this->_pdo->getRow('SELECT u.*, ud.* FROM [users] u LEFT JOIN [users_data] ud ON u.`id`= ud.`user_id` WHERE u.`id` = '.$id);

			if ($data)
			{
				if ($key === NULL)
				{
					return $data;
				}
				elseif (is_array($key))
				{
					$return  = array();
					foreach($key as $val)
					{
						if (isset($data[$val]))
						{
							$return[$val] = $data[$val];
						}
					}

					return $return;
				}
				elseif (isset($data[$key]))
				{
					return $data[$key];
				}
			}
		}
		return FALSE;
	}

	/**
	 * Metoda zwracająca dane użytkownika o danym identyfikatorze
	 * w zależności od podanego klucza
	 *
	 * @param   integer  ID użytkownika
	 * @param   string   indeks tablicy
	 * @return  mixed
	 */
	final public function getByEmail($email, $key = NULL)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			if ($data = $this->_pdo->getRow('SELECT * FROM [users] WHERE `email` = :email', array(':email', $email, PDO::PARAM_STR)))
			{
				if ($key === NULL)
				{
					return $data;
				}
				elseif (is_array($key))
				{
					$return  = array();
					foreach($key as $val)
					{
						if (isset($data[$val]))
						{
							$return[$val] = $data[$val];
						}
					}

					return $return;
				}
				elseif (isset($data[$key]))
				{
					return $data[$key];
				}
			}
		}

		return FALSE;
	}

	/**
	 * Metoda zwracająca dane użytkownika o określonym nicku
	 * w zależności od podanego klucza
	 *
	 * @param   integer  ID użytkownika
	 * @param   string   indeks tablicy
	 * @return  mixed
	 */
	final public function getByUsername($username, $key = NULL)
	{
		if ($this->isValidLogin($username))
		{
			if ($data = $this->_pdo->getRow('SELECT * FROM [users] WHERE `username` = :username', array(':username', $username, PDO::PARAM_STR)))
			{
				if ($key === NULL)
				{
					return $data;
				}
				elseif (is_array($key))
				{
					$return  = array();
					foreach($key as $val)
					{
						if (isset($data[$val]))
						{
							$return[$val] = $data[$val];
						}
					}

					return $return;
				}
				elseif (isset($data[$key]))
				{
					return $data[$key];
				}
			}
		}

		return FALSE;
	}

	/**
	 * Aktualizacja czasu ostatniej aktywności na stronie.
	 *
	 * @return  bool
	 */
	public function updateVisitTime()
	{
		return (bool) $this->_pdo->exec("UPDATE [users] SET `lastvisit`='".time()."' WHERE `id`='{$this->get('id')}'");
	}

	/**
	 * Zwraca IP użytkownika.
	 *
	 * @return  string
	 */
	public function getIP()
	{
		return $this->_ip;
	}

	/**
	 * Sprawdza, czy użytkownik jest zalogowany.
	 *
	 * @return  bool
	 */
	public function isLoggedIn()
	{
		return $this->_logged;
	}

	/**
	 * Usuwanie zmiennych zalogowania.
	 *
	 * @return  void
	 */
	public function userLogout()
	{
		HELP::removeSession('user', 'admin');
		HELP::removeCookie('user');
		$this->setGuestOnline();
	}

	/**
	 * Loguje administratora.
	 *
	 * @param   string  nazwa użytkownika
	 * @param   string  hasło użytkownika
	 * @return  bool
	 */
	public function adminLogin($username, $pass)
	{
		$username = trim($username);

		if ($this->isValidLogin($username))
		{
			$query = $this->_pdo->getRow('SELECT `salt` FROM [users] WHERE `username`= :username', array(
				array(':username', $username, PDO::PARAM_STR)
			));

			if ($query)
			{
				$sha512 = sha512($query['salt'].'^'.$pass);

				$row = $this->_pdo->getRow('SELECT `id`, `roles` FROM [users] WHERE `username` = :username AND `password` = \''.$sha512.'\' AND `status` = 0', array(
					array(':username', $username, PDO::PARAM_STR)
				));

				if ($row)
				{
					$hash = $this->createLoginHash();

					$_SESSION['admin'] = array(
						'id'	 => $row['id'],
						'hash'   => $hash
					);

					$_crypt = new Crypt(CRYPT_KEY, CRYPT_IV);

					// Zapis danych autoryzacji w bazie danych
					$this->_pdo->exec('UPDATE [users] SET `admin_hash` = \''.$hash.'\', `admin_last_logged_in` = '.time().', `browser_info` = \''.$_crypt->encrypt(md5($_SERVER['HTTP_USER_AGENT'])).'\' WHERE `id` = '.$row['id']);

					return TRUE;
				}
			}
		}

		return FALSE;
	}

	/**
	 * Podtrzymywanie sesji zalogowania i tworzenie tablicy danych o administratorze.
	 *
	 * @param   integer
	 * @param   string
	 * @param   integer
	 * @return  bool
	 */
	public function adminLoggedIn($id, $hash)
	{
		if (isNum($id))
		{
			if ($row = $this->_pdo->getRow('SELECT u.*, ud.* FROM [users] u LEFT JOIN [users_data] ud ON u.`id`= ud.`user_id` WHERE u.`id` = '.$id.' AND `status` = 0'))
			{
				if ($row['admin_hash'] === $hash)
				{
					$_crypt = new Crypt(CRYPT_KEY, CRYPT_IV);

					if ($_crypt->decrypt($row['browser_info']) === md5($_SERVER['HTTP_USER_AGENT']))
					{
						if ($row['admin_last_logged_in'] > (time()-$this->_sett->getUns('loging', 'admin_loging_time')))
						{
							$this->_data = $row;

							$hash = $this->createLoginHash();

							$_SESSION['admin']['hash'] = $hash;

							// Aktualizacja danych autoryzacji w bazie danych
							$this->_pdo->exec('UPDATE [users] SET `admin_hash` = \''.$hash.'\' WHERE `id` = '.$row['id']);

							$this->_logged = TRUE;

							return TRUE;
						}
					}
				}
			}
		}

		$this->adminLogout();
		return FALSE;
	}

	/**
	 * Usuwanie zmiennych zalogowania
	 *
	 * @return  void
	 */
	public function adminLogout()
	{
		HELP::removeSession('admin');
	}

	// Zwraca wartość będącą częścią nazwy pliku cache odwiedzającego stronę.
	public function getCacheName()
	{
		if ($this->isLoggedIn())
		{
			return 'user-'.$this->get('id');
		}

		return 'guest';
	}

	##### PERMISSIONS && GROUPS OF PERMISSION #####

	public function getPerms()
	{
		return $this->_perms;
	}

	public function getPermsSections()
	{
		return $this->_perms_sections;
	}

	public function iADMIN()
	{
		return $this->isInGroup(1);
	}

	public function iUSER()
	{
		return $this->isLoggedIn();
	}

	public function iGUEST()
	{
		return ! $this->isLoggedIn();
	}

	public function setRoles($roles, $role, $user = NULL)
	{
		if ($user === NULL)
		{
			$user = $this->get('id');
		}

		//$roles = array_keys($roles);

		return $this->update(array(
			'roles' => serialize($roles),
			'role' => $role
		), $user);
	}

	/**
	 * Sprawdza, czy dany użytkownik posiada określone uprawnienie
	 *
	 * @param   string  nazwa uprawnienia
	 * @return  bool
	 */
	 
	 // Do sprawdzenia... Komentowanie nie działa prawdopobnie przez to.
	public function hasPermission($name, $logged_in = TRUE)
	{
		$roles = $this->getRoles();

		foreach ($roles as $role)
		{
			if (in_array('*', $role['permissions']) || in_array($name, $role['permissions']))
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Zwraca w postaci tablicy dane wszystkich grup, do których użytkownik należy
	 *
	 * @param   int    identyfikator użytkownika
	 * @param   boll   zmienna umożliwiająca przeładowanie cache'u uprawnień (grup)
	 * @return  array
	 */
	protected function getRoles($user = NULL, $reload = FALSE)
	{
		if ($user === NULL)
		{
			if ( ! count($this->_roles) || $reload)
			{
				// Zapisuje dane uprawnień, które zalogowany użytkownik posiada, bądź uprawnienia gościa w przypadku gdy nie jest zalogowany
				$this->setUserRolesCache();
			}
			return $this->_roles;
		}
		else
		{
			$roles = $this->getByID($user, 'roles');
		}

		$roles  = implode(',', unserialize($roles));

		$query = $this->_pdo->getData('SELECT * FROM [groups] WHERE id IN ('.$roles.')');

		$_roles = array();
		foreach($query as $role)
		{
			$_roles[] = array(
				'id' => $role['id'],
				'title' => $role['title'],
				'permissions' => unserialize($role['permissions']),
				'team' => $role['team']
			);
		}

		return $_roles;
	}

	// Zwraca w postaci tablicy identyfikatory grup, do których należy zalogowany użytkownik
	public function getUserGroupsID()
	{
		$data = array();
		foreach($this->_roles as $val)
		{
			$data[] = $val['id'];
		}

		return $data;
	}

	/**
	 * Zwraca gotową dla szablonu tablicę danych istniejących grup
	 * !! DEPRECATED - metoda do usunięcia, starajmy się nie używać.
	 *
	 * @param   void
	 * @return  array
	 */
	public function getViewRoles()
	{echo 'METODA KLASY USER O NAZWIE getViewRoles JEST PRZESTARZALA. PROSZE UZYWAC MULTI UPRAWNIEN I METODY getViewGroups. PRZYKLAD UZYCIA PODANY W KOMENTARZU DO TEJ METODY.';
		$roles = array();
		foreach($this->_all_roles as $val)
		{
			$roles[] = array(
				'ID' => $val['id'],
				'Title' => $val['title']
			);
		}

		return $roles;
	}

	// Zwraca tablicę, gdzie klucz jest indeksem grupy, a wartością jej nazwa.
	// Metoda znakomicie nadaje się do tworzenia listy dostępu, na przykład do Newsa, w połączeniu z metodą Parsera.
	// Przykłąd użycia (pochodzi z pliku Stron):
	//		$_tpl->assign('insight_groups', $_tpl->createSelectOpts($_user->getViewGroups(), NULL, TRUE));
	public function getViewGroups()
	{
		$groups = array();
		foreach($this->_all_roles as $group)
		{
			$groups[$group['id']] = $group['title'];
		}

		return $groups;
	}

	/**
	 * Zwraca w tablicy nazwy grup, do których użytkownik należy
	 *
	 * @param   int    identyfikator użytkownika
	 * @return  array
	 */
	public function getUserRolesTitle($id = NULL)
	{
		if ($id === NULL)
		{
			foreach($this->getRoles() as $val) {
				$_roles[] = $val['title'];
			}
		}
		else
		{
			if (isNum($id))
			{
				foreach($this->getRoles($id) as $val) {
					$_roles[] = $val['title'];
				}
			}
		}
		return $_roles;
	}
	/**
	 * Cache dla grup uprawnień zalogowanego użytkownika
	 *
	 * @param   void
	 * @return  void
	 */
	protected function setUserRolesCache()
	{
		if ($this->isLoggedIn())
		{
			$roles = unserialize($this->get('roles'));

			foreach($roles as $role)
			{
				foreach($this->_all_roles as $val)
				{
					if ($role == $val['id'])
					{
						$this->_roles[] = $val;
					}
				}
			}
		}
		else
		{
			$this->_roles[] = $this->_all_roles[3]; // 3 to id z bazy dla grupy Goście
		}

	}

	/**
	 * Cache dla wszystkich grup
	 *
	 * @param   void
	 * @return  void
	 */
	protected function setAllRolesCache()
	{
		$query = $this->_pdo->getData('SELECT * FROM [groups]');
		foreach($query as $role)
		{
			$role['permissions'] = unserialize($role['permissions']);
			$this->_all_roles[$role['id']] = $role;
		}
	}

	/**
	 * Cache dla wszystkich uprawnień
	 *
	 * @param   void
	 * @return  void
	 */
	protected function setPermissionsCache()
	{
		$r = $this->_pdo->query('SELECT * FROM [permissions]');

		foreach($r as $d)
		{
			$this->_perms[] = $d;
		}
	}

	/**
	 * Cache dla wszystkich sekcji uprawnień
	 *
	 * @param   void
	 * @return  void
	 */
	protected function setPermissionsSectionsCache()
	{
		$r = $this->_pdo->query('SELECT * FROM [permissions_sections]');

		foreach($r as $d)
		{
			$this->_perms_sections[] = $d;
		}
	}

	/**
	 * Zwraca ciąg identyfikatorów grup, do których należy określony użytkownik
	 * Znakomicie nadaje się do pobierania tylko tych elementów z bazy, do których użytkownik ma prawo wglądu
	 *
	 * UWAGA!! Metody tej, przy wstawianiu do zapytań, w których występuję konstrukcja `IN`, nie można bindować.
	 * Jest bezpieczna, więc należy umieścić ją bezpośrednio w zapytaniu.
	 * Wynika to z tego, że wartość w nawiasie kontrukcji `IN` nie może być w apostrofie, a bindowanie jako INT powoduje,
	 * że apostrof jest dodawany, gdyż zwraca przez tą metodę wartość nie jest numeryczna dla ilości grup większej niż jedna.
	 *
	 * @example			$rows = $_pdo->getMatchRowsCount('SELECT `id` FROM [news] WHERE `access` IN ('.$_user->listRoles().') AND `draft` = 0');
	 * @param   string	analizowany indeks tablicy
	 * @param   int    	identyfikator użytkownika
	 * @param   string 	separator oddzielający poszczególne nazwy uprawnień (grup)
	 * @return  string
	 */
	public function listRoles($key = 'id', $user = NULL, $separator = ',')
	{

		if ($this->iAdmin())
		{
			$roles = $this->_all_roles;
		}
		else
		{
			$roles = $this->getRoles($user);
		}

		$_roles = array();
		foreach ($roles as $role)
		{
			// Sprawdzanie, czy wartośc jest numeryczna w celu zabezpieczenia zapytań,
			// gdzie zwracana wartość przez tą metodę jest wprowadzana bezpośrednio do zapytania.
			if (isset($role[$key]) && isNum($role[$key]))
			{
				$_roles[] = $role[$key];
			}
		}

		return implode($separator, $_roles);
	}

	/**
	 * Sprawdza, czy użytkownik przynależy do grupy o podanym parametrem identyfikatorze
	 *
	 * @param   int    	identyfikator grupy
	 * @return  bool
	 */
	public function isInGroup($id)
	{
		if (isNum($id))
		{
			$groups = $this->getRoles();

			foreach($groups as $group)
			{
				if ($id == $group['id'])
				{
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	/**
	 * Sprawdza, czy użytkownik posiada uprawnienie do przeglądu materiału, panela lub podobnej struktury.
	 * Działa też dla multi uprawnień.
	 *
	 * @param   int or array    ID grup/y uprawnień
	 * @return  bool
	 */
	public function hasAccess($id)
	{
		foreach(HELP::explode($id) as $search)
		{
			if (in_array($search, $this->getUserGroupsID()))
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Zwraca nazwę grupy o podanym identyfikatorze
	 * lub tablicę nazw (jeżeli parametr $id jest tablicą),
	 * gdzie indeksy odpowiadają wartościom tablicy $id.
	 *
	 * @param   mixed    identyfikator uprawnienia
	 * @return  mixed
	 */
	public function getRoleName($var)
	{
		if (is_array($var))
		{
			$data = array();
			foreach($var as $val)
			{
				if ($response = $this->getRoleName($val))
				{
					$data[$val] = $response;
				}
			}
			return $data;
		}
		else
		{
			if ( ! empty($this->_all_roles[$var]))
			{
				return $this->_all_roles[$var]['title'];
			}
		}

		return FALSE;
	}

	/**
	 * Przekształca ciąg IDs grup na ciąg nazw grup, jeśli drugi parametr jest ustawiony na TRUE.
	 * W przeciwnym wypadku zwróci tablicę nazw.
	 * Używane przy prezentacji nazw grup, które mają dostęp na przykład do panelu.
	 *
	 * @param   string    ciąg IDs grup uprawnień
	 * @return  string	  ciąg nazw grup uprawnień
	 */
	public function groupsStrIDsToNames($string, $implode = TRUE)
	{
		if ($implode)
		{
			return implode(', ', $this->getRoleName(HELP::explode($string)));
		}

		return $this->getRoleName(HELP::explode($string));
	}

	/**
	 * Zwraca dokładnie to samo co metoda groupsStrIDsToNames() z tą różnicą,
	 * że operuje na tablicy z IDs grup uprawnień, a nie na ciągu.
	 * Jeśli drugi parametr jest ustawiony na TRUE, to metoda zamiast ciągu zwróci tablicę nazw grup.
	 *
	 * PRZYKŁADY UŻYCIA W SYSTEMIE:
	 * W Panelu admina na podstronie Panele do prezentacji uprawnień,
	 * jakie zostaną przypisane przy aktywacji nieaktywnego jeszcze panelu.
	 * WYJAŚNIENIE:
	 * W plikach config.php katalogów paneli identyfikator `access` może przyjmować wartość w postaci stringa
	 * lub tablicy. Wartości indeksów tablicy lub wartość wspomnianego stringa to IDs grup uprawnień,
	 * jakie mają zostać przypisane przy aktywacji panelu.
	 *
	 * @param   string    ciąg IDs grup uprawnień
	 * @return  string	  ciąg nazw grup uprawnień
	 */
	public function groupArrIDsToNames($array, $implode = TRUE)
	{
		if ($implode)
		{
			return implode(', ', $this->getRoleName($array));
		}

		return $this->getRoleName($array);
	}

	/**
	 * Usuwa wszystkim grupom uprawnienia, które już nie istnieją
	 *
	 * @return  void
	 */
	public function cleanPerms()
	{
		foreach($this->_perms as $d)
		{
			$perms[] = $d['name'];
		}

		$r = $this->_pdo->getData('SELECT id, permissions FROM [groups]');
		if ($this->_pdo->getRowsCount($r))
		{
			foreach($r as $d)
			{
				$d['permissions'] = unserialize($d['permissions']);
				$new = array();
				foreach($d['permissions'] as $perm)
				{
					if (in_array($perm, $perms) || $perm === '*')
					{
						$new[] = $perm;
					}
				}
				$up = $this->_pdo->exec("UPDATE [groups] SET `permissions` = '".serialize($new)."' WHERE `id` = '{$d['id']}'");
			}
		}
	}

	/**
	 * Ustawia nie udaną próbę logowania
	 *
	 * @return  bool
	 */
	public function setTryLogin($var)
	{
		$this->_try_login = $var;

		return TRUE;
	}

	/**
	 * Pobiera nie udaną próbę logowania
	 *
	 * @return  bool
	 */
	public function getTryLogin()
	{
		return isset($this->_try_login) ? $this->_try_login : FALSE;
	}

	/*
	abstract function createPermission();
	abstract function createGroup();
	abstract function createPermissionSection();
	abstract function deletePermission();
	abstract function deleteGroup();
	abstract function deletePermissionSection();
	*/

	 /*****************************************************************
	 **				ZLICZANIE ODWIEDZAJACYCH STRONĘ                 **
	 ****************************************************************/
	 protected function cacheOnline()
	 {
		foreach ($this->_pdo->getData('SELECT o.*, u.username FROM [users_online] o LEFT JOIN [users] u ON o.user_id = u.id ORDER BY o.last_activity DESC') as $user)
		{
			//if (in_array($))
			$this->_online[] = $user;
		}

		return $this->_online;
	 }

	/**
	 * Zwraca tablicę ID użytkowników online
	 */
	public function getOnline()
	{
		if ($this->_get_users_online !== array() && !$this->_get_users_online)
		{
			if ( ! $online = $this->_online)
			{
				$online = $this->cacheOnline();
			}

			$data = array();

			$ids = array();
			foreach($online as $row)
			{
				// Czy jest to użytkownik?
				if ($row['user_id'] !== '0' && $row['last_activity'] >= $this->_online_activity)
				{
					if (in_array($row['user_id'], $ids))
					{
						//$this->_online_to_remove[] = $row['id'];
					}
					else
					{
						$ids[] = $row['user_id'];

						$data[] = array(
							'id' => $row['user_id'],
							'username' => $row['username']
						);
					}
				}
			}

			$this->_get_users_online = $data;
			//$this->cleanOnlineDuplicates();
		}
		
		return $this->_get_users_online;
	}

	// deprecated
	/*protected function cleanOnlineDuplicates()
	{
		if ($this->_online_to_remove)
		{
			$data = new Edit($this->_online_to_remove);
			if ($data->isArrayNum(TRUE))
			{
				$ids = implode(',', $data->show());
				//$this->_pdo->exec('DELETE FROM [users_online] WHERE id IN ('.$ids.')');
			}

			$this->_online_to_remove = array();
		}
	}*/

	protected function onlineCleanTable()
	{
		$this->_pdo->exec('DELETE FROM [users_online] WHERE last_activity < '.$this->_online_activity);
	}

	// Zwraca ilość gości online
	public function getGuests()
	{
		if ($this->_get_guests_online === NULL)
		{
			if ( ! $online = $this->_online)
			{
				$online = $this->cacheOnline();
			}

			$ids = array(); $count = 0;
			foreach($online as $row)
			{

				// Jeśli id == 0 to jest to gość
				if ($row['user_id'] === '0' && $row['last_activity'] >= $this->_online_activity)
				{
					if (in_array($row['ip'], $ids))
					{
						//$this->_online_to_remove[] = $row['id'];
					}
					else
					{
						$count++;
						$ids[] = $row['ip'];
					}
				}
			}

			$this->_get_guests_online = $count;
			//$this->cleanOnlineDuplicates();
		}

		return $this->_get_guests_online;
	}

	public function setUserOnline()
	{
		$this->_pdo->exec('DELETE FROM [users_online] WHERE `user_id` = '.$this->get('id').' OR (`user_id` = 0 AND `ip` = \''.$this->getIP().'\')');
		$this->_pdo->exec('INSERT INTO [users_online] (`user_id`, `ip`, `last_activity`) VALUES ('.$this->get('id').', \''.$this->getIP().'\', '.time().')');
	}

	public function setGuestOnline()
	{
		$this->_pdo->exec('DELETE FROM [users_online] WHERE `ip` = \''.$this->getIP().'\''.($this->get('id') ? 'OR `user_id` = '.$this->get('id') : ''));
		$this->_pdo->exec('INSERT INTO [users_online] (`user_id`, `ip`, `last_activity`) VALUES (0, \''.$this->getIP().'\', '.time().')');
	}

	public function updateActivity()
	{
		$id = (int)$this->get('id');
		$this->_pdo->exec('INSERT INTO [users_online] (`user_id`, `ip`, `last_activity`) VALUES ('.$id.', \''.$this->getIP().'\', '.time().') ON DUPLICATE KEY UPDATE `last_activity` = '.time());
	}

	/***********************************************************
	 *							REJESTRACJA
	 **********************************************************/

	/**
	 * Sprawdza poprawność nazwy użytkownika
	 */
	public function validLogin($var)
	{
		return !preg_match("#[^\w\d-]+#i", $var);
	}

	/**
	 * Sprawdza, czy podana nazwa użytkownika jest dostępna.
	 * Przy określeniu drugiego parametru jako TRUE, metoda waliduje podany pierwszym parametrem ciąg
	 * pod kątem poprawności nazwy użytkownika.
	 */
	public function availableLogin($var, $validation = FALSE)
	{
		if ( ! $validation || $this->validLogin($var))
		{
			return ! (bool) $this->_pdo->getRow('SELECT `username` FROM [users] WHERE `username` = :username', array(':username', $var, PDO::PARAM_STR));
		}

		return FALSE;
	}

	/**
	 * Sprawdza poprawność adresu e-mail
	 */
	public function validEmail($var)
	{
		return (bool) filter_var($var, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * Sprawdza, czy podany adres e-mail jest dostępny.
	 * Przy określeniu drugiego parametru jako TRUE, metoda waliduje podany pierwszym parametrem ciąg
	 * pod kątem poprawności adresu e-mail.
	 */
	public function availableEmail($var, $validation = FALSE)
	{
		if ( ! $validation || $this->validEmail($var))
		{
			return ! (bool) $this->_pdo->getRow('SELECT `email` FROM [users] WHERE `email`= :email', array(':email', $var, PDO::PARAM_STR));
		}

		return FALSE;
	}

	/**
	 * Sprawdza poprawność formatu podanych haseł
	 */
	public function validPassword($pass1, $pass2)
	{
		$strlen = strlen($pass1);

		return $pass1 === $pass2 && $strlen > 5 && $strlen < 21 && $pass1;
	}

	public function getEmailHost($email)
	{
		if (strlen($string = strrchr($email, '@')))
		{
			return substr($string, 1);
		}

		return FALSE;
	}

	public function bannedByEmail($email, $validation = FALSE)
	{
		if ( ! $validation || $this->validEmail($var))
		{
			return (bool)
				$this->_pdo->getMatchRowsCount('SELECT `id` FROM [blacklist] WHERE `email` = :email OR `email` = :host',
					array(
						array(':email', $email, PDO::PARAM_STR),
						array(':host', $this->getEmailHost($email), PDO::PARAM_STR)
					)
				);
		}

		return TRUE;
	}

	// Przekierowanie na logowanie z zachowaniem adresu do przeglądanej strony
	public function onlyForUsers($_route)
	{
		if ( ! $this->isLoggedIn())
		{
			HELP::redirect($_route->path(array('controller' => 'login', 'action' => base64_encode($_SERVER['REQUEST_URI']))));
		}
	}

	public function customData()
	{
		if ($this->_custom_data)
		{
			return $this->_user_custom_data;
		}
		else
		{
			return $this->_user_custom_data = new UserCustomData($this, $this->_pdo);
		}
	}
}

class UserCustomData
{
	protected $_user;
	protected $_pdo;

	public function __construct(User $_user, Data $_pdo)
	{
		$this->_user = $_user;
		$this->_pdo = $_pdo;
	}

	public function update($data, $user_id = NULL)
	{
		$field = array(); $value = array(); $index = array();

		foreach($data as $key => $val)
		{
			$field[] = '`'.$key.'` = "'.$val.'"';
			$value[] = '"'.$val.'"';
			$index[] = '`'.$key.'`';
		}

		$field = implode(', ', $field);
		$value = implode(', ', $value);
		$index = implode(', ', $index);

		// Aktualizacja danych wszystkich użytkowników
		if ($user_id === NULL)
		{
			$this->_pdo->exec('UPDATE [users_data] SET '.$field);
		}
		// Aktualizacja danych konkrtetnego użytkownika
		else
		{
			$this->_pdo->exec('INSERT INTO [users_data] (`user_id`, '.$index.') VALUES ('.$user_id.', '.$value.') ON DUPLICATE KEY UPDATE '.$field.'');
		}

		return TRUE;
	}
}