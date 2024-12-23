<?php
/**
*
* @package phpBB Extension - Topic Index
* @copyright (c) 2015 dmzx - https://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\topicindex\core;

use phpbb\db\driver\driver_interface as db_interface;
use phpbb\template\template;
use phpbb\config\config;
use phpbb\auth\auth;
use phpbb\extension\manager;

class functions_topicindex
{
	/** @var db_interface */
	protected $db;

	/** @var template */
	protected $template;

	/** @var config */
	protected $config;

	/** @var auth */
	protected $auth;

	/** @var manager */
	protected $extension_manager;

	/**
	* Constructor
	*
	* @param db_interface	$db
	* @param template		$template
	* @param config			$config
	* @param auth			auth
	* @param manager 		$extension_manager
	*/
	public function __construct(
		db_interface $db,
		template $template,
		config $config,
		auth $auth,
		manager $extension_manager
	)
	{
		$this->db 					= $db;
		$this->template 			= $template;
		$this->config				= $config;
		$this->auth					= $auth;
		$this->extension_manager	= $extension_manager;
	}

	/**
	* Assign topics to the list
	* @retun array
	*/
	function get_topic_index($the_forum, $the_topic, $inlistvalue = 0, $tagfilter = 0)
	{
		$sql = "SELECT t.topic_id, t.topic_title, t.topic_poster, t.topic_first_poster_name, t.topic_first_poster_colour, t.forum_id, t.topic_posts_approved, t.topic_title, t.inindex_topic, t.outindex_topic, f.forum_name
			FROM " . TOPICS_TABLE . " t
			LEFT JOIN " . FORUMS_TABLE . " f ON t.forum_id = f.forum_id
			WHERE t.forum_id = " . (int) $the_forum . "
				AND t.topic_posts_approved >= 1
				AND t.inindex_topic >= " . (int) $inlistvalue . "
				AND t.outindex_topic = 0
			ORDER BY t.topic_title ASC";
		$result	= $this->db->sql_query($sql);

		$topiclist	= array();
		$array		= array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$array['forum'] = $row['forum_name'];
			if ($tagfilter == 1)
			{
				switch (substr(trim($row['topic_title']),0,1))
				{
					case '(':
						$newstring	= substr($row['topic_title'], strpos($row['topic_title'], ')') + 1);
						$newstring	= trim($newstring);

						if (substr($newstring,0,1) == '(')
						{
							$newstring	= substr($newstring, strpos($row['topic_title'], ')') + 1);
							$newstring	= trim($newstring);
							$keytag		= strtolower(substr($newstring,0,1));
							$keyBig		= strtolower(substr($newstring,0,4));
						}
						else
						{
							$keytag		= strtolower(substr($newstring,0,1));
							$keyBig		= strtolower(substr($newstring,0,4));
						}
					break;
					case '[':
						$newstring	= substr($row['topic_title'], strpos($row['topic_title'], ']') + 1);
						$newstring	= trim($newstring);

						if (substr($newstring,0,1) == '[')
						{
							$newstring	= substr($newstring, strpos($row['topic_title'], ']') + 1);
							$newstring	= trim($newstring);
							$keytag		= strtolower(substr($newstring,0,1));
							$keyBig		= strtolower(substr($newstring,0,4));
						}
						else
						{
							$keytag		= strtolower(substr($newstring,0,1));
							$keyBig		= strtolower(substr($newstring,0,4));
						}
					break;
					case '{':
						$newstring	= substr($row['topic_title'], strpos($row['topic_title'], '}') + 1);
						$newstring	= trim($newstring);

						if (substr($newstring,0,1) == '{')
						{
							$newstring	= substr($newstring, strpos($row['topic_title'], '}') + 1);
							$newstring	= trim($newstring);
							$keytag		= strtolower(substr($newstring,0,1));
							$keyBig		= strtolower(substr($newstring,0,4));
						}
						else
						{
							$keytag		= strtolower(substr($newstring,0,1));
							$keyBig		= strtolower(substr($newstring,0,4));
						}
					break;
					default:
						$keytag	= strtolower(substr($row['topic_title'],0,1));
						$keyBig	= strtolower(substr($row['topic_title'],0,4));
					break;
				}
			}
			else
			{
				$keytag	= strtolower(substr($row['topic_title'],0,1));
				$keyBig	= strtolower(substr($row['topic_title'],0,30));
			}

			if (preg_match('#^[a-z]#u', $keytag))
			{
				$keytag;
			}
			else if (preg_match('#^[0-9]#u', $keytag))
			{
				$keytag	= '09';
			}
			else
			{
				$keytag	= '_';
			}

			if ($row['topic_id'] != $the_topic)
			{
				$array['id']		= $row['topic_id'];
				$array['key']		= $keytag;
				$array['keybig']	= $keyBig;
				$array['title']		= $row['topic_title'];
				$array['posts']		= $row['topic_posts_approved'];
				$array['posterid']	= $row['topic_poster'];
				$array['poster']	= $row['topic_first_poster_name'];
				$array['postercol']	= $row['topic_first_poster_colour'];
				$topiclist[] 		= $array;
			}
		}
		$this->db->sql_freeresult($result);

		/* order array */
		if (count($topiclist) > 0)
		{
			foreach ($topiclist as $key => $row)
			{
				$order_in_category[$key] = $row['keybig'];
			}
			array_multisort ($order_in_category, SORT_ASC, $topiclist);
		}

		return	$topiclist;
	}

	/**
	* Create a list of topic key real used for menu
	* @retun array
	*/
	function create_key_list($topicarray)
	{
		$fixa = array();

		foreach ($topicarray as $indexdata)
		{
			$fixa[]	= $indexdata['key'];
		}

		return array_unique($fixa);
	}

	/**
	* Filter topic list array by the alpha call
	* @retun array
	*/
	function filter_topiclist($topicarray, $limiter = '')
	{
		if (!empty($limiter))
		{
			foreach ($topicarray as $key => $subArray)
			{
				if ($subArray['key'] != $limiter)
				{
					unset($topicarray[$key]);
				}
			}
		}
		return $topicarray;
	}

	/**
	* Remove a topic from the list
	*/
	function remove_from_ilist($the_forum, $whoremove = 0)
	{
		$whoremovefixed	= (int) $whoremove;

		if (!empty($whoremovefixed) && $this->auth->acl_getf_global('m_'))
		{
			$sql_arr = array(
				'inindex_topic'		=> 0,
				'outindex_topic'	=> 1
			);

			$sql = "UPDATE " . TOPICS_TABLE . "
				SET " . $this->db->sql_build_array('UPDATE', $sql_arr) . "
				WHERE topic_id = {$whoremovefixed}
					AND forum_id = " . (int) $the_forum;
			$this->db->sql_query($sql);
		}
	}

	/**
	* Add a topic to the list
	*/
	function add_to_ilist($the_forum, $whoadd = 0)
	{
		$whoaddfixed = (int) $whoadd;

		if (!empty($whoaddfixed) && $this->auth->acl_getf_global('m_'))
		{
			$sql_arr = array(
				'inindex_topic'		=> 1,
				'outindex_topic'	=> 0
			);

			$sql = "UPDATE " . TOPICS_TABLE . "
				SET " . $this->db->sql_build_array('UPDATE', $sql_arr) . "
				WHERE topic_id = {$whoaddfixed}
				AND forum_id = " . (int) $the_forum;
			$this->db->sql_query($sql);
		}
	}

	/**
	* Check if the topic is in index list
	*/
	function checkif_inlist($listtype, $inindex, $outindex)
	{
		$listtype	= (int) $listtype;
		$inindex	= (int) $inindex;
		$outindex	= (int) $outindex;

		switch ($listtype)
		{
			case 1:
				$inlist	= ($inindex == 1 && $outindex == 0) ? true : false;
			break;
			case 0:
			default:
				$inlist	= ($outindex == 0) ? true : false;
			break;
		}

		return $inlist;
	}

	/**
	* Get the forum base data
	*/
	function get_ForumBasedata($forum_id)
	{
		$sql2 = 'SELECT forum_id, post_index, default_inindex, tag_filter
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . (int) $forum_id;
		$result2 = $this->db->sql_query($sql2);
		$forum_data	= $this->db->sql_fetchrow($result2);
		$this->db->sql_freeresult($result2);

		$output = array();
		$output['inindex']	= $forum_data['default_inindex'];
		$output['filter']	= $forum_data['tag_filter'];

		return $output;
	}

	function assign_authors()
	{
		$md_manager = $this->extension_manager->create_extension_metadata_manager('dmzx/topicindex', $this->template);
		$meta = $md_manager->get_metadata();

		$author_names = array();
		$author_homepages = array();

		foreach (array_slice($meta['authors'], 0, 1) as $author)
		{
			$author_names[] = $author['name'];
			$author_homepages[] = sprintf('<a href="%1$s" title="%2$s">%2$s</a>', $author['homepage'], $author['name']);
		}
		$this->template->assign_vars(array(
			'TOPICINDEX_DISPLAY_NAME'		=> $meta['extra']['display-name'],
			'TOPICINDEX_AUTHOR_NAMES'		=> implode(' &amp; ', $author_names),
			'TOPICINDEX_AUTHOR_HOMEPAGES'	=> implode(' &amp; ', $author_homepages),
			'TOPICINDEX_VERSION'			=> $this->config['topicindex_version'],
		));

		return;
	}
}
