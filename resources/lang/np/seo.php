<?php

/**
 * SEO Translations - नेपाली (Nepali)
 *
 * Used for server-side fallback meta tags in app.blade.php
 * These are also mirrored in resources/js/locales/np.json for client-side useMeta()
 *
 * Keep in sync with:
 * - resources/lang/en/seo.php (English)
 * - resources/lang/de/seo.php (German)
 * - resources/js/locales/en.json (Vue i18n)
 * - resources/js/locales/de.json (Vue i18n)
 * - resources/js/locales/np.json (Vue i18n)
 */

return [
    'site' => [
        'title' => 'Public Digit',
        'description' => 'विश्वव्यापी प्रवासी समुदायहरु, संस्थाहरु र एनजीओहरुको लागि सुरक्षित डिजिटल मतदान प्लेटफर्म। जीडीपीआर-अनुरूप, अन्त-देखि-अन्त एन्क्रिप्ट गरिएको अनलाइन चुनाव।',
        'keywords' => 'अनलाइन मतदान, डिजिटल चुनाव, प्रवासी मतदान, एनआरएनए चुनाव, सुरक्षित मतदान प्लेटफर्म, इलेक्ट्रोनिक मतदान प्रणाली',
    ],

    'pages' => [
        'home' => [
            'title' => 'सुरक्षित अनलाइन मतदान | Public Digit Elections',
            'description' => 'आपको संस्थालाई सुरक्षित, पारदर्शी अनलाइन मतदान सक्षम गर्नुहोस्। Public Digit ले विश्वव्यापी प्रवासी समुदायहरु, एनजीओ र सदस्यता संस्थाहरुको लागि जीडीपीआर-अनुरूप चुनाव प्रदान गर्छ।',
            'keywords' => 'अनलाइन मतदान, डिजिटल चुनाव, सुरक्षित मतदान, प्रवासी चुनाव, एनआरएनए',
        ],

        'pricing' => [
            'title' => 'मूल्य निर्धारण योजनाहरु | Public Digit Elections',
            'description' => 'सबै आकारका संस्थाहरुको लागि पारदर्शी मूल्य निर्धारण। आपको चुनाव आवश्यकताहरुसँग मिल्ने योजना छान्नुहोस्। कुनै लुकेको शुल्क छैन, एनजीओ र प्रवासी समूहहरुको लागि स्केलेबल समाधान।',
            'keywords' => 'चुनाव मूल्य, मतदान सफ्टवेयर लागत, अनलाइन मतदान प्लेटफर्म, चुनाव समाधान मूल्य',
        ],

        'organizations.show' => [
            'title' => '{organizationName} | चुनावहरु र सदस्यहरु | Public Digit',
            'description' => '{organizationName}: {memberCount} सदस्य, {electionCount} चुनाव। संस्थाहरु र प्रवासी समुदायहरुको लागि सुरक्षित डिजिटल मतदान प्लेटफर्म।',
            'keywords' => '{organizationName}, चुनाव, मतदान, डिजिटल लोकतन्त्र',
        ],

        'elections.index' => [
            'title' => 'सक्रिय चुनावहरु | Public Digit',
            'description' => 'Public Digit प्लेटफर्म भर सक्रिय चुनाव ब्राउज गर्नुहोस्। विश्वव्यापी संस्थाहरुको लागि सुरक्षित, पारदर्शी मतदानमा भाग लिनुहोस्।',
            'keywords' => 'सक्रिय चुनाव, आगामी मतदान, चुनाव सूची, मतदान अवसरहरु',
        ],

        'elections.show' => [
            'title' => '{electionName} | {organizationName} | Public Digit',
            'description' => '{organizationName} को {electionName} को लागि चुनाव जानकारी। पूर्ण अडिट ट्रेल सहित सुरक्षित, पारदर्शी मतदान प्लेटफर्म।',
            'keywords' => '{electionName}, {organizationName}, मतदान, चुनाव परिणाम',
        ],
    ],
];
