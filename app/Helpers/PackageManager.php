<?php

namespace App\Helpers;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Class PackageManager - manage extra packages (add-ons)
 *
 * @package App\Helpers
 */
class PackageManager
{
    private $packages = [];
    private $appDirectory;

    function __construct()
    {
        $this->appDirectory = __DIR__ . '/../../';

        // loop through packages definitions and load config from composer.json
        foreach (glob($this->appDirectory . 'packages/*/config.json') as $configFile) {
            if ($package = json_decode(file_get_contents($configFile))) {
                $this->packages[$package->id] = $package;
                $this->packages[$package->id]->installed = $this->installed($package->id);
                $this->packages[$package->id]->enabled = $this->enabled($package->id);
                if ($this->packages[$package->id]->enabled) {
                    $this->packages[$package->id]->version = config($package->id . '.version');
                }
            }
        }

        return $this;
    }

    /**
     * Get package by its ID
     *
     * @param $id
     * @return null
     */
    public function get($id)
    {
        return $this->packages[$id] ?? NULL;
    }

    /**
     * Get all supported packages
     *
     * @param bool $returnCollection
     * @return array|Collection
     */
    public function getAll($returnCollection = FALSE)
    {
        return $returnCollection ? collect($this->packages) : $this->packages;
    }

    /**
     * Get all installed packages
     *
     * @param bool $returnCollection
     * @return array|Collection
     */
    public function getInstalled($returnCollection = FALSE)
    {
        $result = array_filter($this->packages, function($package) {
            return $package->installed;
        });

        return $returnCollection ? collect($result) : $result;
    }

    /**
     * Get all enabled packages
     *
     * @param bool $returnCollection
     * @return array|Collection
     */
    public function getEnabled($returnCollection = FALSE)
    {
        $result = array_filter($this->packages, function($package) {
            return $package->enabled;
        });

        return $returnCollection ? collect($result) : $result;
    }

    /**
     * Check whether given package is installed
     *
     * @param $id
     * @return bool
     */
    public function installed($id): bool
    {
        $package = $this->get($id);
        return $package && file_exists($this->appDirectory . 'packages/' . $package->base_path . '/' . $package->source_path . '/' . str_replace([$package->namespace, '\\'], ['','/'], $package->providers[0]) . '.php');
    }

    /**
     * Check whether given package is enabled
     *
     * @param $id
     * @return bool
     */
    public function enabled($id): bool
    {
        $package = $this->get($id);
        return $this->installed($id) &&
            isset($package->min_app_version) &&
            version_compare(config('app.version'), $package->min_app_version, '>=') &&
            !Storage::disk('local')->exists(sprintf('packages/%s/disabled', $package->id));
    }

    /**
     * Check whether given package is disabled
     *
     * @param $id
     * @return bool
     */
    public function disabled($id): bool
    {
        return !$this->enabled($id);
    }

    /**
     * Auto load necessary file when a package class is called
     *
     * @param $className
     */
    public function autoload($className)
    {
        foreach ($this->getInstalled() as $package) {
            // classes that are mapped one by one
            $static = (array) $package->static;

            // if specific class (such as seed) should be loaded
            if (in_array($className, array_keys($static))) {
                $classPath = __DIR__ . '/../../packages/' . $package->base_path . '/' . $static[$className] . '/' . $className . '.php';
                include_once $classPath;
            // otherwise try to match by namespace
            } elseif (strpos($className, $package->namespace) !== FALSE) {
                $classPath = __DIR__ . '/../../packages/' . $package->base_path . '/' . $package->source_path . '/' . str_replace([$package->namespace, '\\'], ['','/'], $className) . '.php';
                include_once $classPath;
            }
        }
    }
}
