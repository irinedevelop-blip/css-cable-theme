<?php
namespace CSSC\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

class Products extends Base {
	public function get_name() { return 'cssc-products'; }
	public function get_title() { return 'CSS: Продукция (градиентный блок)'; }
	public function get_icon() { return 'eicon-products'; }

	protected function register_controls() {
		$d = cssc_section_defaults( 'products' );
		$this->start_controls_section( 'content', array( 'label' => 'Контент' ) );
		$this->add_control( 'label', array( 'label' => 'Метка', 'type' => Controls_Manager::TEXT, 'default' => $d['label'] ) );

		$cats = array( '' => '— своя ссылка —' );
		if ( taxonomy_exists( 'product_cat' ) ) {
			$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $t ) { $cats[ $t->term_id ] = $t->name; }
			}
		}
		$r = new Repeater();
		$r->add_control( 'title', array( 'label' => 'Название', 'type' => Controls_Manager::TEXT, 'default' => 'Кабель' ) );
		$r->add_control( 'image', array( 'label' => 'Изображение / постер видео (PNG с прозрачным фоном)', 'type' => Controls_Manager::MEDIA ) );
		$r->add_control( 'video', array( 'label' => 'Видео вместо картинки (URL .webm с прозрачностью или .mp4)', 'type' => Controls_Manager::TEXT, 'label_block' => true, 'description' => 'Загрузите файл в Медиабиблиотеку и вставьте ссылку. Пусто — показывается картинка.' ) );
		$r->add_control( 'marks', array( 'label' => 'Марки (через запятую) — в раскрытой карточке', 'type' => Controls_Manager::TEXTAREA, 'rows' => 2 ) );
		$r->add_control( 'text', array( 'label' => 'Описание — в раскрытой карточке', 'type' => Controls_Manager::TEXTAREA, 'rows' => 6 ) );
		$r->add_control( 'cat', array( 'label' => 'Категория WooCommerce', 'type' => Controls_Manager::SELECT, 'options' => $cats, 'default' => '' ) );
		$r->add_control( 'url', array( 'label' => 'Ссылка', 'type' => Controls_Manager::URL, 'condition' => array( 'cat' => '' ) ) );
		$items = array();
		foreach ( $d['items'] as $it ) {
			$items[] = array( 'title' => $it['title'], 'image' => array( 'url' => $it['image'] ), 'video' => $it['video'], 'marks' => $it['marks'], 'text' => $it['text'], 'url' => array( 'url' => $it['url'] ) );
		}
		$this->add_control( 'items', array( 'label' => 'Карточки', 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => $items, 'title_field' => '{{{ title }}}' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'detail', array( 'label' => 'Раскрытая карточка: значки' ) );
		$this->add_control( 'gost_text', array( 'label' => 'Значок ГОСТ: текст (можно <br>, <b>)', 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => $d['gost_text'] ) );
		$this->add_control( 'warranty', array( 'label' => 'Гарантия: число лет (пусто — скрыть)', 'type' => Controls_Manager::TEXT, 'default' => $d['warranty'] ) );
		$this->add_control( 'warr_text', array( 'label' => 'Гарантия: текст', 'type' => Controls_Manager::TEXTAREA, 'rows' => 2, 'default' => $d['warr_text'] ) );
		$this->add_control( 'more_text', array( 'label' => 'Кнопка перехода в каталог (пусто — скрыть)', 'type' => Controls_Manager::TEXT, 'default' => $d['more_text'] ) );
		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => 'Стиль', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->section_bg( '.cssc-products' );
		$this->add_control( 'grad_from', array( 'label' => 'Градиент: слева', 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => array( '{{WRAPPER}} .cssc-products__card' => '--g-from: {{VALUE}};' ) ) );
		$this->add_control( 'grad_to', array( 'label' => 'Градиент: справа', 'type' => Controls_Manager::COLOR, 'default' => '', 'selectors' => array( '{{WRAPPER}} .cssc-products__card' => '--g-to: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'radius', array(
			'label' => 'Скругление', 'type' => Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
			'selectors' => array( '{{WRAPPER}} .cssc-products__card' => 'border-radius: {{SIZE}}px;' ),
		) );
		$this->add_responsive_control( 'img_h', array(
			'label' => 'Высота изображений', 'type' => Controls_Manager::SLIDER, 'range' => array( 'px' => array( 'min' => 80, 'max' => 500 ) ),
			'selectors' => array( '{{WRAPPER}} .cssc-product__img' => 'height: {{SIZE}}px;' ),
		) );
		$this->style_text( 'ptitle', 'Названия', '.cssc-product__title' );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$items = array();
		foreach ( (array) $s['items'] as $it ) {
			$url = self::url( $it['url'] ?? '' );
			if ( ! empty( $it['cat'] ) ) {
				$l = get_term_link( (int) $it['cat'], 'product_cat' );
				if ( ! is_wp_error( $l ) ) { $url = $l; }
			}
			$items[] = array( 'title' => $it['title'], 'image' => self::media( $it['image'] ), 'video' => $it['video'] ?? '', 'marks' => $it['marks'] ?? '', 'text' => $it['text'] ?? '', 'url' => $url ? $url : '' );
		}
		cssc_render_products( array(
			'label' => $s['label'], 'items' => $items,
			'gost_text' => $s['gost_text'] ?? '', 'warranty' => $s['warranty'] ?? '', 'warr_text' => $s['warr_text'] ?? '', 'more_text' => $s['more_text'] ?? '',
		) );
	}
}
