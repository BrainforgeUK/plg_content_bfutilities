<?php
/**
 * @package Plugin with various utilities, such as current date display and language translation
 * @version 1.0.0
 * @copyright Copyright (C) 2020-2021 Jonathan Brain - brainforge. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 * @author https://www.brainforge.co.uk
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;

// No direct access
defined('_JEXEC') or die;

class plgContentBfutilities extends CMSPlugin
{
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		if (empty($article->text))
		{
			return;
		}

		$article->text = $this->prepareContent($article->text);
	}

	protected function prepareContent($text)
	{
		return preg_replace_callback('/{\s*bfutilities\s+(.*?)\s+(.*?)\s*}/i',
			function ($matches) {
				if (count($matches) != 3)
				{
					return $matches[0];
				}

				switch($matches[1])
				{
					case 'date':
						return date($matches[2]);
					case 'text':
						$args = explode(',', $matches[2]);

						switch (substr($args[0], 0, 4))
						{
							case 'PLG_':
								$extension = explode('_', strtolower($args[0]), 4);
								if (count($extension) == 4)
								{
									unset($extension[3]);
									$lang = Factory::getApplication()->getLanguage();
									$lang->load(implode('_', $extension), JPATH_ADMINISTRATOR);
									$lang->load(implode('_', $extension), JPATH_PLUGINS);
								}
								break;
						}

						if (count($args) == 1)
						{
							return $this->prepareContent(Text::_($matches[2]));
						}

						return $this->prepareContent(call_user_func_array('Joomla\CMS\Language\Text::sprintf', $args));
					default:
						return $matches[0];
				}
			}, $text);
	}
}
?>
