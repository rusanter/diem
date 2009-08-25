<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormFilterInput represents an HTML input tag used for filtering text.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormFilterInput.class.php 13510 2008-11-30 08:56:58Z dwhittle $
 */
class sfWidgetFormDmFilterDate extends sfWidgetFormDateRange
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * with_empty:      Whether to add the empty checkbox (true by default)
   *  * empty_label:     The label to use when using an empty checkbox
   *  * filter_template: The template to use to render the widget
   *                     Available placeholders: %date_range%, %empty_checkbox%, %empty_label%
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('with_empty', true);
    $this->addOption('empty_label', 'is empty');
    $this->addOption('template', dm::getI18n()->__('from', array(), 'admin').' %from_date%<br />'.dm::getI18n()->__('to', array(), 'admin').' %to_date%');
    $this->addOption('filter_template', '<div class="is_empty">%empty_checkbox%%empty_label%</div><div class="date_range">%date_range%</div>');
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $values = array_merge(array('is_empty' => ''), is_array($value) ? $value : array());

    return strtr($this->getOption('filter_template'), array(
      '%date_range%'     => parent::render($name, $value, $attributes, $errors),
      '%empty_checkbox%' => $this->getOption('with_empty') ? $this->renderTag('input', array('type' => 'checkbox', 'name' => $name.'[is_empty]', 'checked' => $values['is_empty'] ? 'checked' : '')) : '',
      '%empty_label%'    => $this->getOption('with_empty') ? $this->renderContentTag('label', dm::getI18n()->__($this->getOption('empty_label'), array(), 'admin'), array('for' => $this->generateId($name.'[is_empty]'))) : '',
    ));
  }

}
