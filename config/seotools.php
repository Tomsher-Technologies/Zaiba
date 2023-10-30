<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => "Industry Tech Store", // set false to total remove
            'titleBefore'  => false, // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description'  => "Industry Tech Store is recognised as one of the leading solution providers in the United Arab Emirates (UAE), Contact us now!", // set false to total remove
            'separator'    => ' - ',
            'keywords'     => ['marine equipment,
            marine products,
            service marine,
            marine offshore companies in uae,
            marine electrical suppliers in uae,
            marine battery suppliers in uae,
            marine equipment and supply,
            marine safety equipment,
            marine navigation lights,
            boat safety equipment,
            marine engine,
            boat accessories,
            boat parts near me,
            marine engine parts,
            marine spare parts suppliers,
            ship spare parts list,
            turbocharger in ship,
            ship automation and control systems,
            marine oil,'],
            'canonical'    => 'full', // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'robots'       => 'index, follow', // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => "Industry Tech Store", // set false to total remove
            'description' => "Industry Tech Store is recognised as one of the leading solution providers in the United Arab Emirates (UAE), Contact us now!", // set false to total remove
            'url'         => null, // Set null for using Url::current(), set false to total remove
            'type'        => false,
            'site_name'   => false,
            'images'      => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            //'card'        => 'summary',
            //'site'        => '@LuizVinicius73',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title'       => "Industry Tech Store", // set false to total remove
            'description' => "Industry Tech Store is recognised as one of the leading solution providers in the United Arab Emirates (UAE), Contact us now!", // set false to total remove, // set false to total remove
            'url'         => 'full', // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'type'        => 'WebPage',
            'images'      => [],
        ],
    ],
];
