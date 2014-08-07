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
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Util\Filesystem;

/**
 * Class AddonInstaller
 * @package iMSCP\Composer
 */
class AddonInstaller extends LibraryInstaller
{
	/**
	 * @var iMSCPConfig
	 */
	protected $imscpConfig;

	/**
	 * {@inheritDoc}
	 */
	public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null)
	{
		// Create imscp config
		$this->imscpConfig = new iMSCPConfig();

		parent::__construct($io, $composer, 'imscp-addon', $filesystem);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPackageBasePath(PackageInterface $package)
	{
		$packageName = $package->getPrettyName();
		$prefix = substr($packageName, 0, 21);

		if ('imscp-packages/addon-' !== $prefix) {
			throw new \InvalidArgumentException(
				sprintf(
					'Unable to install the %s addon, any addon must start its package name with imscp-packages/addon-"',
					$packageName
				)
			);
		}

		$this->filesystem->ensureDirectoryExists($this->imscpConfig['PACKAGES_ROOT_DIR']);

		return $this->imscpConfig['PACKAGES_ROOT_DIR'] . '/' . ucfirst(substr($packageName, 21));
	}

	/**
	 * {@inheritDoc}
	 */
	//public function supports($packageType)
	//{
	//	return 'imscp-addon' === $packageType;
	//}
}
