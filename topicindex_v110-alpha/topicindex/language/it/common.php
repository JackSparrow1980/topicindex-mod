<?php
/**
*
* @package phpBB Extension - Topic Index
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ADD_INILIST'					=> '+Lista',
	'REMOVE_INILIST'				=> '-Lista',
	'OTINDEX_LINK_TITLE'			=> 'Lista Argomenti',
	'NUM'							=> 'N°',
	'POSTER'						=> 'Pubblicato da',
	'INDEX_POST_PRES'				=> 'Prima di aprire un nuovo argomento, controlla se non è già presente in questo elenco. Se c’è, utilizza quel topic per i tuoi post.<br />Grazie',
	'NON_ACTIVE'					=> 'Inattivo',
	'DEF_ALL_TOPICS_IN'				=> 'Tutti i nuovi argomenti automaticamente',
	'DEF_ALL_TOPICS_OFF'			=> 'Aggiungi manualmente gli argomenti all\'elenco',
	'REMOVE_FROM_LIST'				=> 'Rimuovi',
	'TOPICINDEX_VERSION'			=> 'Versione',
	'ALL'							=> 'Tutti',
	'09'							=> '0-9',
	'SYMB'							=> '#&%',
	'ANY_TOPIC_IN_LIST'				=> 'Qualsiasi argomento',
	'SELECT_A_LIST'					=> 'Seleziona un elenco',
	'OIINDEX_HEADER'				=> 'Lista Argomenti',
	'FORUM' 						=> 'Forum',
));
