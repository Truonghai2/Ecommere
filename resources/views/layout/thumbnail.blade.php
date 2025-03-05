@if ($type == 0)
<section
class="elementor-section elementor-top-section elementor-element elementor-element-8e52d56 elementor-section-content-middle elementor-section-boxed elementor-section-height-default elementor-section-height-default"
data-id="8e52d56" data-element_type="section"
data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
<div class="container-fluid">
    <div class="row flex-wrap">
        <div class="col-md-4 col-12 d-flex mb-4">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-element elementor-element-a5695bb elementor-widget elementor-widget-heading"
                    data-id="a5695bb" data-element_type="widget" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <style>
                            .elementor-heading-title {
                                padding: 0;
                                margin: 0;
                                line-height: 1
                            }

                            .elementor-widget-heading .elementor-heading-title[class*=elementor-size-]>a {
                                color: inherit;
                                font-size: inherit;
                                line-height: inherit
                            }

                            .elementor-widget-heading .elementor-heading-title.elementor-size-small {
                                font-size: 15px
                            }

                            .elementor-widget-heading .elementor-heading-title.elementor-size-medium {
                                font-size: 19px
                            }

                            .elementor-widget-heading .elementor-heading-title.elementor-size-large {
                                font-size: 29px
                            }

                            .elementor-widget-heading .elementor-heading-title.elementor-size-xl {
                                font-size: 39px
                            }

                            .elementor-widget-heading .elementor-heading-title.elementor-size-xxl {
                                font-size: 59px
                            }
                        </style>
                        <h1 class="elementor-heading-title elementor-size-default">{{ $title }}</h1>
                        <div class="btn btn-primary mt-3 bg-color border-color">
                            Khán phá
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12 mb-4">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-element elementor-element-7a6ba45 elementor-widget elementor-widget-image"
                    data-id="7a6ba45" data-element_type="widget" data-widget_type="image.default">
                    <div class="elementor-widget-container">
                        <style>
                            /*! elementor - v3.21.0 - 26-05-2024 */
                            .elementor-widget-image {
                                text-align: center
                            }

                            .elementor-widget-image a {
                                display: inline-block
                            }

                            .elementor-widget-image a img[src$=".svg"] {
                                width: 48px
                            }

                            .elementor-widget-image img {
                                vertical-align: middle;
                                display: inline-block
                            }
                        </style> <img fetchpriority="high" decoding="async" class="img-fluid"
                            src="{{ $image }}" alt=""
                            srcset="{{ $image }} 658w, {{ $image }} 300w, {{ $image }} 600w, {{ $image }} 400w"
                            sizes="(max-width: 658px) 100vw, 658px">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12 d-flex mb-4">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-element elementor-element-83797af elementor-widget elementor-widget-heading"
                    data-id="83797af" data-element_type="widget" data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <h4 class="elementor-heading-title elementor-size-default">Giới thiệu</h4>
                    </div>
                </div>
                <div class="elementor-element elementor-element-e179eac elementor-widget elementor-widget-text-editor"
                    data-id="e179eac" data-element_type="widget" data-widget_type="text-editor.default">
                    <div class="elementor-widget-container">
                        <style>
                            /*! elementor - v3.21.0 - 26-05-2024 */
                            .elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap {
                                background-color: #69727d;
                                color: #fff
                            }

                            .elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap {
                                color: #69727d;
                                border: 3px solid;
                                background-color: transparent
                            }

                            .elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap {
                                margin-top: 8px
                            }

                            .elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap-letter {
                                width: 1em;
                                height: 1em
                            }

                            .elementor-widget-text-editor .elementor-drop-cap {
                                float: left;
                                text-align: center;
                                line-height: 1;
                                font-size: 50px
                            }

                            .elementor-widget-text-editor .elementor-drop-cap-letter {
                                display: inline-block
                            }
                        </style>
                        <p class="mt-4">{{ $content }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@elseif($type == 1)
<div class="container-content elementor-section container row mx-auto justify-content-between py-5 px-lg-5 px-md-3 px-2">
    <div class="title mt-2 col-lg-5 col-md-8 col-12 mb-4 mb-lg-0">
        <span class="text-black font-size-24 text-bold" data-aos="fade-right">{{ $title }}</span>
        <div class="description mt-3">
            <span class="text-wrap">
                {{ $content }}
            </span>
        </div>
    </div>

    <div class="thumbnail col-lg-5 col-md-8 col-12">
        <img class="img-fluid rounded shadow" src="{{ $image }}" alt="">
    </div>
</div>
@endif



