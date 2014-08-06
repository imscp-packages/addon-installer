<?php

namespace iMSCP\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Class AddonInstaller
 * @package iMSCP\Composer
 */
class AddonInstaller extends LibraryInstaller
{
	/**
	 * {@inheritDoc}
	 */
	public function getPackageBasePath(PackageInterface $package)
	{
		$prefix = substr($package->getPrettyName(), 0, 21);

		if ('imscp-packages/addon-' !== $prefix) {
			throw new \InvalidArgumentException(
				'Unable to install addon, i-MSCP addons should always start their package name with '
				. '"imscp-packages/addon-"'
			);
		}

		return '/usr/local/imscp/addons/' . substr($package->getPrettyName(), 21);
	}

	/**
	 * {@inheritDoc}
	 */
	public function supports($packageType)
	{
		return 'imscp-addon' === $packageType;
	}
}
