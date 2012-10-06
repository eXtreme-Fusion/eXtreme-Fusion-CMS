<?php
/**
* File: Browser.php
* Author: Chris Schuld (http://chrisschuld.com/)
* Last Modified: 2/24/2009
* @version 1.0
* @package PegasusPHP
*
* The Format class is a static class allowing a quick interface for
* applications.  Currently it supports formatting the following:
*
* US Phone Numbers
* US Phone Numbers for Storage
* US Dollars
* Bytes
* KiloBytes
*
* Typical Usage:
*
* echo Format::phoneNumberForStorage('480-373-6581').'<br/>';
* echo Format::phoneNumberForStorage('373-6581').'<br/>';
* echo Format::phoneNumber(Format::phoneNumberForStorage('800-959-0045')).'<br/>';
* echo Format::phoneNumber('3736581').'<br/>';
* echo Format::phoneNumber('3736581','480').'<br/>';
* echo Format::usd(56.32).'<br/>';
* echo Format::bytes(20375237).'<br/>';
* echo Format::kilobytes(20375237).'<br/>';
*
* @package PegasusPHP
*/

class Format {

	const DATETIME_FORMAT = 'd-m-Y, g:i';
	const DATE_FORMAT = 'd-m-Y';
	const TIME_FORMAT = 'g:i';

	/**
	 * Gets a formatted phone numbers
	 * @param $strPhone string contains the phone number to format
	 * @param $strAreaCode string contains the default area code to use if one is not supplied
	 * @return string an unpacked and formatted phone number
	 */
	public static function phoneNumber($strPhone,$strAreaCode='') {
		$strExtension = '';
		$strPhone = strtolower(preg_replace('/[^0-9Xx]/','',$strPhone));
		$extensionPosition = strpos($strPhone,'x');
		if( $extensionPosition != false ) {
			$strExtension = substr($strPhone,$extensionPosition+1,-1);
			$strPhone = substr($strPhone,0,$extensionPosition);
		}
		switch( strlen($strPhone) ) {
			case(10):
				$strPhone = '('.substr($strPhone,0,3).') '.substr($strPhone,3,3).'-'.substr($strPhone,6,4);
				break;
			case(7):
				$strPhone = ($strAreaCode == '' ? '' : '('.$strAreaCode.') ') . substr($strPhone,0,3).'-'.substr($strPhone,3,4);
				break;
			default:
				break;
		}
		return $strPhone . ($strExtension==''?'':' x'.$strExtension);
	}
	/**
	 * Gets a string value of a phone number formatted strictly for storage.
	 * It will only contain numbers and an extension marker
	 * @param $value string contains the phone number to pack for storage
	 * @return string a phone number packed for storage
	 */
	public static function phoneNumberForStorage($value) {
		return strtolower(preg_replace('/[^0-9Xx]/','',$value));
	}
	/**
	 * Get a formated US Dollar amount from a double/float/fp
	 * @param $value double/float/fp number
	 * @return string formatted dollar amount
	 */
	public static function usd($value) {
		return sprintf('$%.2f',$value);
	}

	/**
	* Get bytes converted to a formatted number
	* @param int $bytes        bytes
	* @return string
	*/
	public static function bytes($bytes) {

		$b = (int)$bytes;
	$s = array('B', 'kB', 'MB', 'GB', 'TB');

	if($b <= 0){
		return "0 ".$s[0];
	}

	$con = 1024;
	$e = (int)(log($b,$con));
	return number_format($b/pow($con,$e),2,'.',',').' '.$s[$e];
	}
	/**
	* Get kilobytes converted to a formatted number
	* @param int $kbytes kilobytes
	* @return string
	*/
	public static function kilobytes($kbytes) {

		$kb = (int)$kbytes;
		$s = array('kB', 'MB', 'GB', 'TB');

		if($kb <= 0){
			return "0 ".$s[0];
		}

		$con = 1024;
		$e = (int)(log($kb,$con));
		return number_format($kb/pow($con,$e),2,'.',',').' '.$s[$e];
	}
}