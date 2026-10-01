<?php
namespace CSSC\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * Базовый виджет секции страницы «О компании». Поля строятся из cssc_about_schema().
 */
abstract class About_Base extends Base {
	protected $slug = '';

	public function get_name() { return 'cssc-about-' . $this->slug; }
	public function get_title() { $s = cssc_about_schema(); return $s[ $this->slug ]['title']; }
	public function get_icon() { $s = cssc_about_schema(); return $s[ $this->slug ]['icon']; }
	public function get_categories() { return array( 'css-cable-about' ); }
	public function get_keywords() { return array( 'css', 'about', 'о компании', $this->slug ); }

	private static function ctype( $t ) {
		$map = array( 'text' => Controls_Manager::TEXT, 'textarea' => Controls_Manager::TEXTAREA, 'wysiwyg' => Controls_Manager::WYSIWYG, 'media' => Controls_Manager::MEDIA, 'switch' => Controls_Manager::SWITCHER );
		return $map[ $t ] ?? Controls_Manager::TEXT;
	}

	protected function register_controls() {
		$sc = cssc_about_schema();
		$this->start_controls_section( 'content', array( 'label' => 'Контент' ) );
		foreach ( $sc[ $this->slug ]['fields'] as $key => $f ) {
			list( $type, $label, $def ) = $f;
			if ( 'repeater' === $type ) {
				$r = new Repeater();
				foreach ( $f[3] as $sk => $sf ) {
					$r->add_control( $sk, array( 'label' => $sf[1], 'type' => self::ctype( $sf[0] ), 'label_block' => true ) );
				}
				$this->add_control( $key, array( 'label' => $label, 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => $def, 'title_field' => '{{{ ' . $f[4] . ' }}}' ) );
				continue;
			}
			$args = array( 'label' => $label, 'type' => self::ctype( $type ), 'label_block' => 'text' === $type || 'textarea' === $type );
			if ( 'media' === $type ) { $args['default'] = array( 'url' => $def ); }
			elseif ( 'switch' === $type ) { $args['default'] = $def; $args['return_value'] = 'yes'; }
			else { $args['default'] = $def; }
			if ( 'video' === $key || 'video_full' === $key ) { $args['description'] = 'Загрузите файл в Медиабиблиотеку и вставьте ссылку.'; }
			$this->add_control( $key, $args );
		}
		$this->end_controls_section();

		$this->start_controls_section( 'style', array( 'label' => 'Стиль', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'section_bg', array( 'label' => 'Фон секции', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ab' => 'background-color: {{VALUE}};' ) ) );
		$this->add_responsive_control( 'section_pad', array(
			'label' => 'Отступы секции', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em', '%' ),
			'selectors' => array( '{{WRAPPER}} .ab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
		) );
		$this->add_control( 'accent', array( 'label' => 'Акцентный цвет', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ab' => '--ab-red: {{VALUE}}; --ab-red-l: {{VALUE}};' ) ) );
		$this->add_control( 'eyebrow_color', array( 'label' => 'Надзаголовок: цвет', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ab-eyebrow' => 'color: {{VALUE}};' ) ) );
		$this->add_control( 'title_color', array( 'label' => 'Заголовок: цвет', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ab-h' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typo', 'label' => 'Заголовок: шрифт', 'selector' => '{{WRAPPER}} .ab-h' ) );
		$this->add_control( 'text_color', array( 'label' => 'Текст: цвет', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ab p, {{WRAPPER}} .ab-text' => 'color: {{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'text_typo', 'label' => 'Текст: шрифт', 'selector' => '{{WRAPPER}} .ab-text, {{WRAPPER}} .ab-text-lg, {{WRAPPER}} .ab-lead' ) );
		$this->end_controls_section();
	}

	protected function render() {
		$sc  = cssc_about_schema();
		$raw = $this->get_settings_for_display();
		$s   = array();
		foreach ( $sc[ $this->slug ]['fields'] as $key => $f ) {
			$v = $raw[ $key ] ?? $f[2];
			if ( 'media' === $f[0] ) { $v = self::media( $v, '' ); }
			if ( 'repeater' === $f[0] ) {
				$v = array_map( function ( $it ) use ( $f ) {
					$o = array();
					foreach ( array_keys( $f[3] ) as $sk ) { $o[ $sk ] = $it[ $sk ] ?? ''; }
					return $o;
				}, (array) $v );
			}
			$s[ $key ] = $v;
		}
		cssc_about_render( $this->slug, $s );
	}
}

class About_Hero extends About_Base { protected $slug = 'hero'; }
class About_Intro extends About_Base { protected $slug = 'intro'; }
class About_Timeline extends About_Base { protected $slug = 'timeline'; }
class About_Production extends About_Base { protected $slug = 'production'; }
class About_Numbers extends About_Base { protected $slug = 'numbers'; }
class About_Products extends About_Base { protected $slug = 'products'; }
class About_Advantages extends About_Base { protected $slug = 'advantages'; }
class About_Quality extends About_Base { protected $slug = 'quality'; }
class About_Projects extends About_Base { protected $slug = 'projects'; }
class About_Team extends About_Base { protected $slug = 'team'; }
class About_Final extends About_Base { protected $slug = 'final'; }
