<?php
namespace CSSC\Widgets;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class News extends Base {
	public function get_name() { return 'cssc-news'; }
	public function get_title() { return 'CSS: Новости'; }
	public function get_icon() { return 'eicon-posts-grid'; }

	protected function register_controls() {
		$d = cssc_section_defaults( 'news' );
		$this->start_controls_section( 'content', array( 'label' => 'Контент' ) );
		$this->add_control( 'title', array( 'label' => 'Заголовок', 'type' => Controls_Manager::TEXT, 'default' => $d['title'] ) );
		$this->add_control( 'text', array( 'label' => 'Описание', 'type' => Controls_Manager::TEXTAREA, 'default' => $d['text'] ) );
		$this->add_control( 'count', array( 'label' => 'Количество записей', 'type' => Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 12 ) );
		$cats = array( '' => 'Все рубрики' );
		foreach ( get_categories( array( 'hide_empty' => false ) ) as $c ) { $cats[ $c->slug ] = $c->name; }
		$this->add_control( 'category', array( 'label' => 'Рубрика', 'type' => Controls_Manager::SELECT, 'options' => $cats, 'default' => '' ) );
		$this->add_control( 'note', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => 'Новости берутся из «Записей». Если записей нет — показываются демо-карточки.', 'content_classes' => 'elementor-descriptor' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => 'Стиль', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->section_bg( '.cssc-news' );
		$this->style_text( 'title', 'Заголовок', '.h-display' );
		$this->style_text( 'ntitle', 'Заголовки карточек', '.news-card__title a' );
		$this->add_control( 'badge_bg', array( 'label' => 'Плашка даты: фон', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .date-badge' => 'background-color: {{VALUE}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		cssc_render_news( array( 'title' => $s['title'], 'text' => $s['text'], 'count' => $s['count'], 'category' => $s['category'] ) );
	}
}
