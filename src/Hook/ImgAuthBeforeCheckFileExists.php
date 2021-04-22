<?php
/**
 * Hook handler base class for MediaWiki hook ImgAuthBeforeCheckFileExists in
 * NSFileRepoConnector nsfr_img_auth.php
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * This file is part of BlueSpice MediaWiki
 * For further information visit https://bluespice.com
 *
 * @author     Patric Wirth
 * @package    BlueSpiceNSFileRepoConnector
 * @copyright  Copyright (C) 2021 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GPL-3.0-only
 * @filesource
 */
namespace BlueSpice\NSFileRepoConnector\Hook;

use BlueSpice\Hook;
use Config;
use IContextSource;

abstract class ImgAuthBeforeCheckFileExists extends Hook {

	/**
	 *
	 * @var string
	 */
	protected $path = null;

	/**
	 *
	 * @var string
	 */
	protected $name = null;

	/**
	 *
	 * @var string
	 */
	protected $filename = null;

	/**
	 * @param string &$path
	 * @param string &$name
	 * @param array &$filename
	 * @return bool
	 */
	public static function callback( &$path, &$name, &$filename ) {
		$className = static::class;
		$hookHandler = new $className(
			null,
			null,
			$path,
			$name,
			$filename
		);
		return $hookHandler->process();
	}

	/**
	 *
	 * @param IContextSource $context
	 * @param Config $config
	 * @param string &$path
	 * @param string &$name
	 * @param array &$filename
	 */
	public function __construct( $context, $config, &$path, &$name, &$filename ) {
		parent::__construct( $context, $config );

		$this->path = &$path;
		$this->name = &$name;
		$this->filename = &$filename;
	}

}
