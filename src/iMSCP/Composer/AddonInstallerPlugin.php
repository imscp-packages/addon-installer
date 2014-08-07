<?php
/**
 * i-MSCP - internet Multi Server Control Panel
 * Copyright (C) 2013 - 2014 Laurent Declercq <l.declercq@nuxwin.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace iMSCP\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Class AddonInstallerPlugin
 * @package iMSCP\Composer
 */
class AddonInstallerPlugin implements PluginInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function activate(Composer $composer, IOInterface $io)
	{
		$installer = new AddonInstaller($io, $composer);
		$composer->getInstallationManager()->addInstaller($installer);
	}
}
