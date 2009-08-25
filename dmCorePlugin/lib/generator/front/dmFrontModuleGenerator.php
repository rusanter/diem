<?php

abstract class dmFrontModuleGenerator
{

  protected
  $module,
  $dispatcher,
  $filesystem;

  public function __construct(dmProjectModule $module, sfEventDispatcher $dispatcher)
  {
    $this->module = $module;
    $this->dispatcher = $dispatcher;
    $this->filesystem = new dmFilesystem();
  }

  abstract public function execute();

}