<?php

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
