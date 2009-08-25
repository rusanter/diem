<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage config
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfDefineEnvironmentConfigHandler.class.php 9085 2008-05-20 01:53:23Z Carl.Vondrick $
 */
class dmInlineConfigHandler extends sfYamlConfigHandler
{

  protected $separator = ".";

  /**
   * Executes this configuration handler.
   *
   * @param  string $configFiles An absolute filesystem path to a configuration file
   *
   * @return string Data to be written to a cache file
   *
   * @throws sfConfigurationException If a requested configuration file does not exist or is not readable
   * @throws sfParseException If a requested configuration file is improperly formatted
   */
  public function execute($configFiles)
  {
    // parse the yaml
    $config = self::getConfiguration($configFiles);

    $values = array();
    foreach ($config as $prefix => $categories)
    {
	    foreach ($categories as $category => $keys)
	    {
        $values = array_merge($values, $this->getValues($prefix, $category, $keys));
	    }
    }

    $data = '';
    foreach ($values as $key => $value)
    {
      $data .= sprintf("  '%s' => %s,\n", $key, var_export($value, true));
    }

    // compile data
    $retval = '';
    if ($values)
    {
      $retval = "<?php\n".
                "// auto-generated by dmInlineConfigHandler\n".
                "// date: %s\nreturn array(\n%s);\n";
      $retval = sprintf($retval, date('Y/m/d H:i:s'), $data);
    }

    return $retval;
  }

  /**
   * Gets values from the configuration array.
   *
   * @param string $prefix    The prefix name
   * @param string $category  The category name
   * @param mixed  $keys      The key/value array
   *
   * @return array The new key/value array
   */
  protected function getValues($prefix, $category, $keys)
  {
    // loop through all key/value pairs
    foreach ($keys as $key => $value)
    {
      $values[$prefix.$this->separator.$category.$this->separator.$key] = $value;
    }

    return $values;
  }
  /**
   * @see sfConfigHandler
   */
  static public function getConfiguration(array $configFiles)
  {
    return self::parseYamls($configFiles);
  }
}
