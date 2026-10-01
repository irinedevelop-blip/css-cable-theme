<?php
namespace CSSC\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Deliveries extends Base {
	public function get_name() { return 'cssc-deliveries'; }
	public function get_title() { return 'CSS: Поставки (слайдер)'; }
	public function get_icon() { return 'eicon-slider-push'; }

	protected function register_controls() {
		$d = cssc_section_defaults( 'deliveries' );
		$this->start_controls_section( 'content', array( 'label' => 'Заголовок и счётчик' ) );
		$this->add_control( 'title', array( 'label' => 'Заголовок', 'type' => Controls_Manager::TEXT, 'default' => $d['title'] ) );
		$this->add_control( 'text', array( 'label' => 'Описание', 'type' => Controls_Manager::TEXTAREA, 'default' => $d['text'] ) );
		$this->add_control( 'counter', array( 'label' => 'Число', 'type' => Controls_Manager::TEXT, 'default' => $d['counter'] ) );
		$this->add_control( 'counter_suffix', array( 'label' => 'Знак после числа', 'type' => Controls_Manager::TEXT, 'default' => $d['counter_suffix'] ) );
		$this->add_control( 'counter_label', array( 'label' => 'Подпись', 'type' => Controls_Manager::TEXT, 'default' => $d['counter_label'] ) );
		$this->end_controls_section();

		$this->start_controls_section( 'slides_sec', array( 'label' => 'Слайды (проекты)' ) );
		$r = new Repeater();
		$r->add_control( 'image', array( 'label' => 'Фото', 'type' => Controls_Manager::MEDIA ) );
		$r->add_control( 'tags', array( 'label' => 'Марки кабеля (через запятую)', 'type' => Controls_Manager::TEXT ) );
		$r->add_control( 'category', array( 'label' => 'Тип объекта', 'type' => Controls_Manager::TEXT ) );
		$r->add_control( 'title', array( 'label' => 'Название объекта', 'type' => Controls_Manager::TEXT ) );
		$r->add_control( 'url', array( 'label' => 'Ссылка', 'type' => Controls_Manager::URL ) );
		$slides = array();
		foreach ( $d['slides'] as $sl ) {
			$slides[] = array( 'image' => array( 'url' => $sl['image'] ), 'tags' => $sl['tags'], 'category' => $sl['category'], 'title' => $sl['title'], 'url' => array( 'url' => $sl['url'] ) );
		}
		$this->add_control( 'slides', array( 'label' => 'Слайды', 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => $slides, 'title_field' => '{{{ title }}}' ) );
		$this->add_control( 'autoplay', array( 'label' => 'Автопрокрутка, сек (0 — выкл.)', 'type' => Controls_Manager::NUMBER, 'default' => 0, 'min' => 0 ) );
		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => 'Стиль', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->section_bg( '.cssc-deliveries' );
		$this->style_text( 'title', 'Заголовок', '.h-display' );
		$this->style_text( 'text', 'Описание', '.cssc-deliveries__text' );
		$this->style_text( 'num', 'Число', '.cssc-counter__num' );
		$this->add_responsive_control( 'slide_h', array(
			'label' => 'Высота слайда', 'type' => Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 200, 'max' => 800 ) ),
			'selectors' => array( '{{WRAPPER}} .cssc-slide' => 'height: {{SIZE}}px;' ),
		) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$slides = array();
		foreach ( (array) $s['slides'] as $sl ) {
			$slides[] = array( 'image' => self::media( $sl['image'] ), 'tags' => $sl['tags'], 'category' => $sl['category'], 'title' => $sl['title'], 'url' => self::url( $sl['url'] ) );
		}
		if ( ! empty( $s['autoplay'] ) ) {
			echo '<div data-autoplay="' . esc_attr( (int) $s['autoplay'] ) . '">';
		}
		cssc_render_deliveries( array(
			'title'          => $s['title'],
			'text'           => $s['text'],
			'counter'        => $s['counter'],
			'counter_suffix' => $s['counter_suffix'],
			'counter_label'  => $s['counter_label'],
			'slides'         => $slides,
		) );
		if ( ! empty( $s['autoplay'] ) ) {
			echo '</div>';
		}
	}
}
