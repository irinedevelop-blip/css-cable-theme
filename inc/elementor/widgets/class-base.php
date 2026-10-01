<?php
namespace CSSC\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

abstract class Base extends Widget_Base {
	public function get_categories() { return array( 'css-cable' ); }

	/** Вспомогательный метод: цвет + типографика для селектора */
	protected function style_text( $id, $label, $selector, $with_typo = true ) {
		$this->add_control( $id . '_color', array(
			'label'     => $label . ': цвет',
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} ' . $selector => 'color: {{VALUE}};' ),
		) );
		if ( $with_typo ) {
			$this->add_group_control( Group_Control_Typography::get_type(), array(
				'name'     => $id . '_typo',
				'label'    => $label . ': шрифт',
				'selector' => '{{WRAPPER}} ' . $selector,
			) );
		}
	}

	protected function section_bg( $selector, $label = 'Фон секции' ) {
		$this->add_control( 'section_bg', array(
			'label'     => $label,
			'type'      => Controls_Manager::COLOR,
			'selectors' => array( '{{WRAPPER}} ' . $selector => 'background-color: {{VALUE}};' ),
		) );
		$this->add_responsive_control( 'section_pad', array(
			'label'      => 'Внутренние отступы',
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => array( 'px', 'em', '%' ),
			'selectors'  => array( '{{WRAPPER}} ' . $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
	}

	protected static function url( $v ) {
		return is_array( $v ) ? ( $v['url'] ?? '#' ) : (string) $v;
	}

	protected static function media( $v, $fallback = '' ) {
		return ( is_array( $v ) && ! empty( $v['url'] ) ) ? $v['url'] : $fallback;
	}
}
