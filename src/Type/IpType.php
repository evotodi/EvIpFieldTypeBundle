<?php

namespace Evotodi\IpFieldTypeBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class IpType extends AbstractType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		parent::configureOptions($resolver);
		$resolver->setDefined(array('version', 'readonly'));
		$resolver->setDefaults(array(
			'version' => 'ipv4',
			'readonly' => false
		));
	}

	public function getParent()
	{
		return TextType::class;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		if ($options['version'] == 'ipv4') {
			$ipConf = [
				'ip_conf' => array('version' => 4,
					'max_value' => 255,
					'group' => 4,
					'group_length' => 7,
					'sep' => '.',
					'base' => 10,
					'chars_max' => 3,)
			];
			$view->vars = array_replace($view->vars, $ipConf);
			$view->vars['attr']['data-ip_conf'] = json_encode(array_map('utf8_encode', $ipConf['ip_conf']));
		}else if ($options['version'] == 'ipv6') {
			$ipConf = [
				'ip_conf' => array('version' => 6,
					'max_value' => 0xffff,
					'group' => 8,
					'group_length' => 7,
					'sep' => ':',
					'base' => 16,
					'chars_max' => 4,)
			];
			$view->vars = array_replace($view->vars, $ipConf);
			$view->vars['attr']['data-ip_conf'] = json_encode(array_map('utf8_encode', $ipConf['ip_conf']));
		}else if ($options['version'] == 'mac') {
			$ipConf = [
				'ip_conf' => array('version' => 'mac',
					'max_value' => 0xff,
					'group' => 6,
					'group_length' => 5,
					'sep' => ':',
					'base' => 16,
					'chars_max' => 2,)
			];
			$view->vars = array_replace($view->vars, $ipConf);
			$view->vars['attr']['data-ip_conf'] = json_encode(array_map('utf8_encode', $ipConf['ip_conf']));
		}else{
			$view->vars = array_replace($view->vars, array(
				'ip_conf'          => $options['version']
			));
		}
		$view->vars = array_replace($view->vars, array('readonly' => $options['readonly']));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'ip';
	}
}