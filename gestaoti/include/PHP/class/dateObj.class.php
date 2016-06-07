<?php
/**
 * classe pour la gestion de date
 * date manipulation
 * some stuff comes from SPLIB (DateMath.php)
 *
 * @author lx barjon <lx-dateobj@iassa.com>
 * @version 0.1
 *
 */
class dateObj {
	/**
	 * Date de référence / current object date
	 * @var string
	 * @access private
	 */
	var $baseDate;
	/**
	 * timestamp
	 * @var int
	 * @access private
	 */
	var $timestamp;
	/**
	 * separateur date ('-' par défaut) / date separator
	 * @var string
	 * @access private
	 */
	var $dateSeparator;
	var $dateSeparator2;
	/**
	 * separateur time (':' par défaut) / time separator
	 * @var string
	 * @access private
	 */
	var $timeSeparator;
	/**
	 * separateur Datetime (' ' par défaut) / date/time separator
	 * @var string
	 * @access private
	 */
	var $dateTimeSeparator;
	/**
	 * liste des jours en français (à modifier dans le constructeur pour une autre langue)
	 * @var array
	 * @access private
	 */
	var $lgueDays;
	/**
	 * liste des mois en français (à modifier dans le constructeur pour une autre langue)
	 * @var array
	 * @access private
	 */
	var $lgueMonths;

	/**
	 * -------------------------------------------------------
	 * METHODES PUBLIQUES / PUBLIC METHODS
	 * -------------------------------------------------------
	 */
	/**
	 * Constructeur / Constructor
	 *
	 * @param 	string $baseDate Date pour initialiser l'objet / date to init the object
	 * @acces 	public
	 */
	function dateObj( $baseDate = '' ) {
		$this->dateSeparator = '-';
		$this->dateSeparator2 = '/';
		$this->timeSeparator = ':';
		$this->dateTimeSeparator = ' ';
		if ( empty($baseDate) ) $this->baseDate = date("Y-m-d H:i:s");
		else $this->baseDate = $baseDate;
		$this->timestamp = $this->date_to_timestamp();

		$this->lgueDays = array(
			'Sunday'=>'dimanche',
			'Sun'=>'dim',
			'Monday'=>'lundi',
			'Mon'=>'lun',
			'Tuesday'=>'mardi',
			'Tue'=>'mar',
			'Wednesday'=>'mercredi',
			'Wed'=>'mer',
			'Thursday'=>'jeudi',
			'Thu'=>'jeu',
			'Friday'=>'vendredi',
			'Fri'=>'ven',
			'Saturday'=>'samedi',
			'Sat'=>'sam'
		);

		$this->lgueMonths = array(
			'January'=>'janvier',
			'Jan'=>'jan',
			'February'=>'février',
			'Feb'=>'fév',
			'March'=>'mars',
			'Mar'=>'mar',
			'April'=>'avril',
			'Apr'=>'avr',
			'May'=>'mai',
			'June'=>'juin',
			'Jun'=>'juin',
			'July'=>'juillet',
			'Jul'=>'juil',
			'August'=>'août',
			'Aug'=>'aoû',
			'September'=>'septembre',
			'Sep'=>'sep',
			'October'=>'octobre',
			'Oct'=>'oct',
			'November'=>'novembre',
			'Nov'=>'nov',
			'December'=>'décembre',
			'Dec'=>'déc'
		);

	}
	/**
	 * set_date
	 *
	 * @desc 	change la date de l'objet / set date for the object
	 * @param 	string Nouvelle date
	 * @access 	public
	 */
	function set_date( $date ) {
		$this->baseDate = $date;
		$this->timestamp = $this->date_to_timestamp();
	}
	/**
	 * date_to_timestamp
	 *
	 * @desc 	retourne un timestamp de la date fournie
	 			ou de la date de l'objet si aucune date fournie
	 			/ return $date timestamp or object date timestamp
	 * @param 	string $date Date à traiter
	 * @access 	public
	 */
	function date_to_timestamp( $date = '' ) {
		if ( empty($date) ) $date = $this->baseDate;
		### recup date dans un tableau assoc
		$dateInfo = $this->date_to_array( $date );
	    return mktime( $dateInfo['H'], $dateInfo['i'], $dateInfo['s'],
	    			$dateInfo['m'], $dateInfo['d'], $dateInfo['Y']);
	}
	/**
	 * date_add
	 *
	 * @desc 	ajoute $howMany à $toWhat sur la date de l'objet et retourne la date au format $format
	 * @param 	int $howMany
	 * @param 	string $toWhat Valeur possible / possible value
	 			Y m d H i s
	 * @param 	string $format
	 * @access 	public
	 */
	function date_add( $howMany, $toWhat, $format = "Y-m-d H:i:s" ) {
		$dateInfo = $this->date_to_array();
		$dateInfo[$toWhat] += $howMany;
	    $tmpDate = date( "Y-m-d H:i:s", mktime( $dateInfo['H'], $dateInfo['i'], $dateInfo['s'], $dateInfo['m'], $dateInfo['d'], $dateInfo['Y']) );
	    return $this->format( $format, $tmpDate );
	}
	/**
	 * date_sub
	 *
	 * @desc 	retire $howMany à $toWhat sur la date de l'objet et retourne la date au format $format
	 * @param 	int $howMany
	 * @param 	string $toWhat Valeur possible / possible value
	 			Y m d H i s
	 * @param 	string $format
	 * @access 	public
	 */
	function date_sub( $howMany, $toWhat, $format = "Y-m-d H:i:s" ) {
		$dateInfo = $this->date_to_array();
		$dateInfo[$toWhat] -= $howMany;
	    $tmpDate = date( "Y-m-d H:i:s", mktime( $dateInfo['H'], $dateInfo['i'], $dateInfo['s'], $dateInfo['m'], $dateInfo['d'], $dateInfo['Y']) );
	    return $this->format( $format, $tmpDate );
	}
	/**
	 * get_timestamp
	 *
	 * @desc 	retourne le timestamp de la date de l'objet
	 * @access 	public
	 */
	function get_timestamp() {
		return $this->timestamp;
	}
	/**
	 * date_to_array
	 *
	 * @desc retourne un tableau associatif avec les paramètres de la date fournie
	 * @param string $date Date à traiter, si vide -> date de ref de l'objet
	 * @return array
	 */
	function date_to_array( $dateTime = '' ) {
		if ( empty($dateTime) ) $dateTime = $this->baseDate;
		$arrayDate = array();
		### verif présence heure
		$timePos = strpos( $dateTime, $this->dateTimeSeparator );
		if ( $timePos ) {
			$date = substr( $dateTime, 0, $timePos );
			$time = substr( $dateTime, $timePos+strlen($timeSeparator), strlen($dateTime) );
		} else $date = $dateTime;
		### Traitement date
		# -- verif si date valide
		$dateIsValid = true;
		# *** date au format d m Y ?
		$pattern = "([0-9]{1,2})".$this->dateSeparator."([0-9]{1,2})".$this->dateSeparator."([0-9]{4})";
		if ( ereg($pattern, $date, $regs) ) {
			$arrayDate['Y'] = intval($regs[3]);
			$arrayDate['m'] = intval($regs[2]);
			$arrayDate['d'] = intval($regs[1]);
		} else {
			# *** sinon date au format y m d ?
			$pattern = "([0-9]{4})".$this->dateSeparator."([0-9]{1,2})".$this->dateSeparator."([0-9]{1,2})";
			if ( ereg($pattern, $date, $regs) ) {
				$arrayDate['Y'] = intval($regs[1]);
				$arrayDate['m'] = intval($regs[2]);
				$arrayDate['d'] = intval($regs[3]);
			# *** sinon date invalide
			} else {
				// Verificar com o outro separador
				# *** date au format d m Y ?
				$pattern = "([0-9]{1,2})".$this->dateSeparator2."([0-9]{1,2})".$this->dateSeparator2."([0-9]{4})";
				if ( ereg($pattern, $date, $regs) ) {
					$arrayDate['Y'] = intval($regs[3]);
					$arrayDate['m'] = intval($regs[2]);
					$arrayDate['d'] = intval($regs[1]);
				} else {
					$dateIsValid = false;
				}
			}
		}
		if ( !$dateIsValid ) {
			trigger_error( '<p>classe dateObj::date_to_array($date) --> A data prevista é inválida!<br>Data prevista : '.$dateTime.'</p>', E_USER_ERROR );
		}

		### Traitement heure
		# -- heure existe ?
		if ( isset($time) ) {
	    	$hour = explode( $this->timeSeparator, $time );
	    	$arrayDate['H'] = intval($hour[0]);
	    	$arrayDate['i'] = intval($hour[1]);
	    	$arrayDate['s'] = intval($hour[2]);
		# -- sinon 0 par défaut
		} else {
	    	$arrayDate['H'] = 0;
	    	$arrayDate['i'] = 0;
	    	$arrayDate['s'] = 0;
	    }

	    return $arrayDate;
	}
	/**
	 * format
	 *
	 * @desc met la date $date au format $format (compatible fonction date();)
	 * @param string $format Format désiré pour la date (ex: "le d-m-Y à H:i:s")
	 * @param string $date Date à traiter, si false -> date de ref de l'objet
	 * @return string
	 */
	function format( $format, $date = '' ) {
		if ( empty($date) ) $timestamp = $this->timestamp;
		else {
			$timestamp = $this->date_to_timestamp( $date );
		}
		$mydate = date($format, $timestamp);
		$days = $this->_en_notations_list($this->lgueDays);
		if ( preg_match("(".$days.")", $mydate, $regs) ) {
			$mydate = str_replace( $regs[0], $this->lgueDays[$regs[0]], $mydate );
		}
		$months = $this->_en_notations_list($this->lgueMonths);
		if ( preg_match("(".$months.")", $mydate, $regs) ) {
			$mydate = str_replace( $regs[0], $this->lgueMonths[$regs[0]], $mydate );
		}
		return $mydate;
	}
	/**
	 * diff
	 *
	 * @desc effectue this->timestamp - timestamp($date)
	 * @param string $date Date à soustraire à la date de ref
	 * @param string $retMode Mode pour l'info de retour
	      valeur possible :
	      jours, days, heures, hours, minutes, mins, all
	      ou vide -> valeur par défaut = secondes
	 * @param string $roundMode Fonction à utiliser pour l'arrondi des calculs -> none = pas d'arrondi
	 */
	function diff( $date, $retMode = '', $roundMode = 'ceil' ) {
		if ( empty($date) ) {
			trigger_error("classe dateObj::diff($date) --> A data a subtrair não está definida.", E_USER_ERROR );
		} else {
			$diffSecondes = ( $this->timestamp - $this->date_to_timestamp($date) );
			$diff = array();
			switch ($retMode) {
				case 'all' :
				{
					$diffMinutes = bcdiv( $diffSecondes, 60, 2 );
					$diffHours = bcdiv( $diffMinutes, 60, 2 );
					$numHours = bcmod( $diffHours, 24 );
					$numMinutes = bcmod( $diffMinutes, 60);
					$numSeconde = bcmod( $diffSecondes, 60 );
					$diff['days'] = abs(bcdiv( $diffHours, 24 ));
					$diff['hours'] = abs($numHours);
					$diff['minutes'] = abs($numMinutes);
					$diff['secondes'] = abs($numSeconde);
					return $diff;
					break;
				}
				case 'annees' :
				case 'years' :
				case 'Y' :
				{
					$diffDays = bcdiv( $diffSecondes, 86400, 2 );
					if ( $roundMode != 'none' ) eval( "\$diffYears = $roundMode( bcdiv( $diffDays, 365, 2 ) );" );
					else $diffYears = bcdiv( $diffDays, 365, 2 );
					return $diffYears;
					break;
				}
				case 'jours' :
				case 'days' :
				case 'd' :
				{
					if ( $roundMode != 'none' ) eval( "\$diffDays = $roundMode( bcdiv($diffSecondes, 86400, 2) );" );
					else $diffDays = bcdiv($diffSecondes, 86400, 2);
					return $diffDays;
					break;
				}
				case 'heures' :
				case 'hours' :
				case 'H' :
				{
					if ( $roundMode != 'none' ) eval( "\$diffHours = $roundMode( bcdiv( $diffSecondes, 3600, 2 ) );" );
					else $diffHours = bcdiv( $diffSecondes, 3600, 2 );
					return $diffHours;
					break;
				}
				case 'minutes' :
				case 'mins' :
				case 'i' :
				{
					if ( $roundMode != 'none' ) eval( "\$diffMinutes = $roundMode( bcdiv( $diffSecondes, 60, 2 ) );" );
					else $diffMinutes = bcdiv( $diffSecondes, 60, 2 );
					return $diffMinutes;
					break;
				}
				default :
				{
					return $diffSecondes;
					break;
				}
			}
		}
	}
	/**
	 * Les fonction qui suivent permettent de vérifier si la date courante :
	 * some function to check if current date (object date) :
	 *		est égale à $date / is equal to $date -> is_eq
	 *		est plus grande que $date / is greater than $date -> is_gt
	 *		est plus grande ou égale à $date / is greater than or equal to $date -> is_gte
	 * 		...
	 */
	function is_eq( $date ) {
		$myTimestamp = $this->date_to_timestamp( $date );
		if ( $this->timestamp == $myTimestamp ) return true;
		else return false;
	}

	function is_gt( $date ) {
		$myTimestamp = $this->date_to_timestamp( $date );
		if ( $this->timestamp > $myTimestamp ) return true;
		else return false;
	}

	function is_gte( $date ) {
		$myTimestamp = $this->date_to_timestamp( $date );
		if ( $this->timestamp >= $myTimestamp ) return true;
		else return false;
	}

	function is_lt( $date ) {
		$myTimestamp = $this->date_to_timestamp( $date );
		if ( $this->timestamp < $myTimestamp ) return true;
		else return false;
	}

	function is_lte( $date ) {
		$myTimestamp = $this->date_to_timestamp( $date );
		if ( $this->timestamp <= $myTimestamp ) return true;
		else return false;
	}

	### useful stuff from DateMath.php
	# -- this one is modified to match my language
	function day_of_week( $numeric = false ) {
		if ( $numeric ) {
			return date('w', $this->timestamp);
		} else {
			return $this->lgueDays[date('l', $this->timestamp)];
		}
	}

	function day_of_year() {
		return date('z', $this->timestamp);
	}

	function week_of_year() {
		return date('W', $this->timestamp);
	}

	function days_in_month() {
		return date('t', $this->timestamp);
	}

	function is_leap_year() {
		return date('L', $this->timestamp);
	}

	function first_day_in_month( $numeric = false ) {
		$firstDay = mktime( 0, 0, 0, date('m', $this->timestamp), 1, date('Y', $this->timestamp) );
		if ( $numeric ) {
			return date('w', $firstDay);
		} else {
			return $this->lgueDays[date('l', $firstDay)];
		}
	}

	/**
	 *
	 */

	function _en_notations_list( $notationsType ) {
		$enNotations = array_keys( $notationsType );
		return '('.implode( '|', $enNotations ).')';
	}
}
?>