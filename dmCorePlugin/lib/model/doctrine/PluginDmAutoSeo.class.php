<?php

/**
 * PluginDmAutoSeo
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 5845 2009-06-09 07:36:57Z jwage $
 */
abstract class PluginDmAutoSeo extends BaseDmAutoSeo
{
  public function getOrCreateCurrentTranslation()
  {
    $translation = $this->getCurrentTranslation();
    
    if($translation->exists())
    {
      return $translation;
    }
    elseif($fallback = $this->getI18nFallback())
    {
      return $fallback;
    }

    /*
     * No translation, no available fallback, let's create a new translation
     */
    $autoSeo = $this->getTable()->createFromModuleAndAction($this->module, $this->action);
    $translation->fromArray($autoSeo->Translation[self::getDefaultCulture()]->toArray());
    $translation->id = $this->id;
    $translation->save();

    return $translation;
  }
  
  public function getTargetDmModule()
  {
    if ($this->hasCache('target_module'))
    {
      return $this->getCache('target_module');
    }
    
    return $this->setCache('target_module', $this->getModuleManager()->getModule($this->module));
  }
  
  public function __toString()
  {
    return $this->getTargetDmModule()->getPlural();
    
//    return sprintf('DmAutoSeo #%d %s.%s',
//      $this->getId(),
//      $this->getModule(),
//      $this->getAction()
//    );
  }
}