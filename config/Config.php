<?php

namespace Naga\Core\Config;

use Naga\Core\Exception\ConfigException;
use Naga\Core\FileSystem\iFileSystem;
use Naga\Core\nComponent;

/**
 * Helper class for managing configurations.
 *
 * @author BlindingLight<bloodredshade@gmail.com>
 * @package Naga\Core\Config
 */
class Config extends nComponent
{
	/**
	 * Config constructor. You can set an iFileSystem instance.
	 *
	 * @param iFileSystem $fileSystem
	 */
	public function __construct(iFileSystem $fileSystem)
	{
		if ($fileSystem)
			$this->setFileSystem($fileSystem);
	}

	/**
	 * Adds a ConfigBag.
	 *
	 * @param $name
	 * @param ConfigBag $config
	 * @return \Naga\Core\Config\Config
	 */
	public function add($name, ConfigBag $config)
	{
		$this->registerComponent($name, $config);
		return $this;
	}

	/**
	 * Returns the ConfigBag instance with the specified name if Config called as a function.
	 * Example: $app->config('application')
	 *
	 * @param $name
	 * @return \Naga\Core\Config\ConfigBag
	 */
	public function __invoke($name)
	{
		return $this->getConfigBag($name);
	}

	/**
	 * Returns the ConfigBag instance with the specified name if accessed like a property.
	 * Example: $app->config->application
	 *
	 * @param $name
	 * @return \Naga\Core\Config\ConfigBag
	 */
	public function __get($name)
	{
		return $this->getConfigBag($name);
	}

	/**
	 * Gets a config array from a file, creates a ConfigBag instance with the name of the file
	 * (excluding extension) and returns the instance.
	 *
	 * @param string $filePath
	 * @return \Naga\Core\Config\ConfigBag
	 */
	public function getFile($filePath)
	{
		$bag = new ConfigBag($this->fileSystem());
		$extension = $this->fileSystem()->extension($filePath);
		if ($extension == 'json')
			$bag->mergeWith((array)json_decode($this->fileSystem()->get($filePath)));
		else
			$bag->getFile($filePath);

		return $this->add(str_replace('.' . $extension, '', $this->fileSystem()->baseName($filePath)), $bag);
	}

	/**
	 * Gets config arrays from files in a directory and creates ConfigBag instance for each file.
	 * ConfigBag instance name will be the file name without extension.
	 *
	 * @param string $directory
	 * @param string|null $extension
	 * @return \Naga\Core\Config\ConfigBag
	 */
	public function getFilesInDirectory($directory, $extension = null)
	{
		$files = $this->fileSystem()->glob($directory . '/*' . ($extension ? '.' . $extension : ''));
		foreach ($files as $file)
			$this->getFile($file);

		return $this;
	}

	/**
	 * Sets the iFileSystem instance.
	 *
	 * @param iFileSystem $fileSystem
	 */
	public function setFileSystem(iFileSystem $fileSystem)
	{
		$this->registerComponent('fileSystem', $fileSystem);
	}

	/**
	 * Gets the iFileSystem instance.
	 *
	 * @return \Naga\Core\FileSystem\iFileSystem
	 */
	protected function fileSystem()
	{
		return $this->component('fileSystem');
	}

	/**
	 * Gets a config.
	 *
	 * @param $name
	 * @return callable|nComponent
	 * @throws \Naga\Core\Exception\ConfigException
	 */
	public function getConfigBag($name)
	{
		try
		{
			return $this->component($name);
		}
		catch (\Exception $e)
		{
			throw new ConfigException("Can't get config with name '{$name}'.");
		}
	}

	/**
	 * Determines whether there is a ConfigBag with the specified name.
	 *
	 * @param string $name
	 * @return bool
	 */
	public function exists($name)
	{
		return $this->componentRegistered($name);
	}
}