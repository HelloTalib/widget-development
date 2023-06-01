<?php

namespace Elementor;

class ELEMENTOR_WIDGET extends Widget_Base {

    public function get_name() {
        return 'elementor_widget';
    }

    public function get_title() {
        return __('Elementor Widget', 'widget-development');
    }

    public function get_icon() {
        return 'eicon-nerd';
    }

    public function get_categories() {
        return ['widget-development'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            '_content_section',
            [
                'label' => __('Content', 'widget-development'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'title',
            [
                'label'       => __('Title', 'widget-development'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Hello World', 'widget-development'),
                'placeholder' => __('Type your title here', 'widget-development'),
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            '_style_section',
            [
                'label' => __('Style', 'flex-themes-quiz-form'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'widget-development'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo '<h2 style="color:' . $settings['title_color'] . '">' . $settings['title'] . '</h2>';
    }
}
