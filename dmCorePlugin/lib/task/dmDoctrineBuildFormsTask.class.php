<?php

/*
 * Je modifie la classe sfDoctrineFormFormGenerator en dmDoctrineFormFormGenerator
 * qui, lui, va gérer l'internationalisation de :
 * label "is empty"
 * options "yes", "no", "yes or no"
 */

class dmDoctrineBuildFormsTask extends sfDoctrineBuildFormsTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    parent::configure();
    $this->aliases = array();
    $this->namespace = 'dm';
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
    {
      sfContext::createInstance($this->configuration);
    }

    $this->logSection('doctrine', 'generating form classes');
    $databaseManager = new sfDatabaseManager($this->configuration);
    $generatorManager = new sfGeneratorManager($this->configuration);
    $generatorManager->generate('dmDoctrineFormGenerator', array(
      'model_dir_name' => $options['model-dir-name'],
      'form_dir_name'  => $options['form-dir-name'],
    ));

    /*
     * Create BaseForm
     */
    $baseFormPath = dmOs::join(sfConfig::get('sf_lib_dir'), 'form', 'BaseForm.class.php');
    if (!file_exists($baseFormPath))
    {
    	/*
    	 * WARNING
    	 * I write
    	 * class"." "."BaseForm
    	 * and not
    	 * class BaseForm
    	 * not to perturb class autoloading
    	 */
    	file_put_contents($baseFormPath, "<?php
/**
 * Base project form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
class"." "."BaseForm extends dmForm
{
}");
    }

    $properties = parse_ini_file(sfConfig::get('sf_config_dir').DIRECTORY_SEPARATOR.'properties.ini', true);

    $constants = array(
      'PROJECT_NAME' => isset($properties['symfony']['name']) ? $properties['symfony']['name'] : 'symfony',
      'AUTHOR_NAME'  => isset($properties['symfony']['author']) ? $properties['symfony']['author'] : 'Your name here'
    );

    // customize php and yml files
    $finder = sfFinder::type('file')->name('*.php');
    $this->getFilesystem()->replaceTokens($finder->in(sfConfig::get('sf_lib_dir').'/form/'), '##', '##', $constants);

    $this->reloadAutoload();
  }
}
