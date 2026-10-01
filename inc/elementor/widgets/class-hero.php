<?php
namespace CSSC\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Hero extends Base {
	public function get_name() { return 'cssc-hero'; }
	public function get_title() { return 'CSS: Первый экран'; }
	public function get_icon() { return 'eicon-header'; }

	protected function register_controls() {
		$d = cssc_section_defaults( 'hero' );

		$this->start_controls_section( 'content', array( 'label' => 'Контент' ) );
		$this->add_control( 'title', array( 'label' => 'Заголовок', 'type' => Controls_Manager::TEXTAREA, 'default' => $d['title'] ) );
		$this->add_control( 'subtitle', array( 'label' => 'Подзаголовок', 'type' => Controls_Manager::TEXT, 'default' => $d['subtitle'] ) );

		$r = new Repeater();
		$r->add_control( 'text', array( 'label' => 'Текст', 'type' => Controls_Manager::TEXT, 'default' => 'Кнопка' ) );
		$r->add_control( 'url', array( 'label' => 'Ссылка (#zayavka — открыть форму заявки)', 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );
		$btns = array();
		foreach ( $d['buttons'] as $b ) {
			$btns[] = array( 'text' => $b['text'], 'url' => array( 'url' => $b['url'] ) );
		}
		$this->add_control( 'buttons', array( 'label' => 'Кнопки', 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => $btns, 'title_field' => '{{{ text }}}' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'aside', array( 'label' => 'Правая колонка (реестр КТП)' ) );
		$this->add_control( 'badge', array( 'label' => 'Логотип', 'type' => Controls_Manager::MEDIA, 'default' => array( 'url' => $d['badge'] ) ) );
		$this->add_control( 'badge_text', array( 'label' => 'Текст', 'type' => Controls_Manager::TEXTAREA, 'default' => $d['badge_text'] ) );
		$this->add_responsive_control( 'badge_w', array(
			'label' => 'Ширина логотипа', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ),
			'range' => array( 'px' => array( 'min' => 40, 'max' => 400 ) ),
			'selectors' => array( '{{WRAPPER}} .cssc-hero__badge' => 'width: {{SIZE}}{{UNIT}};' ),
		) );
		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => 'Стиль', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->section_bg( '.cssc-hero' );
		$this->style_text( 'title', 'Заголовок', '.cssc-hero__title' );
		$this->style_text( 'sub', 'Подзаголовок', '.cssc-hero__sub' );
		$this->style_text( 'aside', 'Текст справа', '.cssc-hero__aside p' );
		$this->add_control( 'btn_bg', array( 'label' => 'Кнопки: фон', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .btn--accent' => 'background-color: {{VALUE}};' ) ) );
		$this->add_control( 'btn_color', array( 'label' => 'Кнопки: текст', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .btn--accent' => 'color: {{VALUE}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		cssc_render_hero( array(
			'title'      => $s['title'],
			'subtitle'   => $s['subtitle'],
			'buttons'    => array_map( function ( $b ) { return array( 'text' => $b['text'], 'url' => self::url( $b['url'] ) ); }, (array) $s['buttons'] ),
			'badge'      => self::media( $s['badge'] ),
			'badge_text' => $s['badge_text'],
		) );
	}
}
