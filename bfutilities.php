<?php
/**
 * @package Plugin with various utilities, such as current date display and language translation
 * @version 1.0.0
 * @copyright Copyright (C) 2020 Jonathan Brain - brainforge. All rights reserved.
 * @license GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 * @author https://www.brainforge.co.uk
 */

// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;

//Factory::getLanguage()->load('plg_content_bfdate', __DIR__);

class plgContentbfutilities extends CMSPlugin
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
					case 'translate':
						return $this->prepareContent(Text::_($matches[2]));
					default:
						return $matches[0];
				}
			}, $text);
	}
}
?>
