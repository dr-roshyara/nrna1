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

        'organisations.show' => [
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

        'election.result' => [
            'title' => '{electionName} परिणाम | Public Digit Elections',
            'description' => '{electionName} को अन्तिम परिणाम। चुनाव परिणाम, उमेद्वारहरुको स्थिति र पूर्ण मतदान सांख्यिकी हेर्नुहोस्।',
            'keywords' => '{electionName}, चुनाव परिणाम, मतदान परिणाम, चुनाव नतिजा',
        ],

        'login' => [
            'title'       => 'साइन इन | Public Digit',
            'description' => 'आफ्नो Public Digit खातामा साइन इन गर्नुहोस् आफ्नो चुनावहरु पहुँच गर्न, मतदान गर्न, वा आफ्नो संस्था व्यवस्थापन गर्न।',
            'keywords'    => 'लग इन, साइन इन, Public Digit खाता, मतदान प्लेटफर्म लगइन',
            'robots'      => 'index, follow',
        ],

        'register' => [
            'title'       => 'खाता सिर्जना गर्नुहोस् | Public Digit',
            'description' => 'आफ्नो संस्थाको लागि सुरक्षित अनलाइन मतदान प्रयोग गर्न Public Digit खातामा दर्ता गर्नुहोस्।',
            'keywords'    => 'दर्ता, खाता सिर्जना, साइन अप, Public Digit दर्ता',
            'robots'      => 'index, follow',
        ],

        'about' => [
            'title'       => 'Public Digit बारे | सुरक्षित डिजिटल मतदान प्लेटफर्म',
            'description' => 'डिजिटल लोकतन्त्रलाई संस्थाहरु, एनजीओहरु र विश्वभरका प्रवासी समुदायहरुको लागि सुरक्षित, पारदर्शी र सुलभ बनाउने Public Digit को मिशनको बारेमा जान्नुहोस्।',
            'keywords'    => 'public digit बारे, डिजिटल मतदान मिशन, सुरक्षित चुनाव प्लेटफर्म, प्रवासी मतदान',
            'robots'      => 'index, follow',
        ],

        'faq' => [
            'title'       => 'FAQ | बारम्बार सोधिने प्रश्नहरु | Public Digit',
            'description' => 'अनलाइन मतदान, सुरक्षा, गोपनीयता र Public Digit ले आफ्नो संस्थाको लागि कसरी काम गर्छ भन्ने सामान्य प्रश्नहरुको जवाफ खोज्नुहोस्।',
            'keywords'    => 'FAQ, बारम्बार सोधिने प्रश्न, अनलाइन मतदान मद्दत, मतदान प्लेटफर्म प्रश्नहरु',
            'robots'      => 'index, follow',
        ],

        'security' => [
            'title'       => 'सुरक्षित र गुमनाम अनलाइन मतदान | Public Digit',
            'description' => 'आफ्नो चुनावहरु सुरक्षित गर्ने पाँच-तह सुरक्षा वास्तुकला। सम्पूर्ण मतदाता गुमनामिता, क्रिप्टोग्राफिक प्रमाणीकरण, र संस्थाहरु, एनजीओ र संगठनहरुको लागि बहु-भाडाधारी अलगाव।',
            'keywords'    => 'सुरक्षित अनलाइन मतदान, गुमनाम मतदान, चुनाव सुरक्षा, डिजिटल मतदान प्लेटफर्म, मतदाता गुमनामिता',
            'robots'      => 'index, follow',
        ],

        'demo' => [
            'title'       => 'डेमो चुनाव प्रयास गर्नुहोस् | Public Digit',
            'description' => 'हाम्रो अन्तरक्रियात्मक डेमो चुनावसँग सुरक्षित अनलाइन मतदान प्रत्यक्ष अनुभव गर्नुहोस्। दर्ता आवश्यक छैन।',
            'keywords'    => 'डेमो चुनाव, मतदान प्रयास, अनलाइन मतदान डेमो, चुनाव प्लेटफर्म परीक्षण',
            'robots'      => 'index, follow',
        ],

        'dashboard' => [
            'title'       => 'ड्यासबोर्ड | Public Digit',
            'description' => 'आफ्नो व्यक्तिगत ड्यासबोर्डबाट आफ्नो चुनावहरु, मतदान गतिविधिहरु र खाता व्यवस्थापन पहुँच गर्नुहोस्।',
            'keywords'    => 'ड्यासबोर्ड, मेरो चुनावहरु, मतदान ड्यासबोर्ड',
            'robots'      => 'noindex, nofollow',
        ],

        'profile' => [
            'title'       => 'तपाईंको प्रोफाइल | Public Digit',
            'description' => 'आफ्नो Public Digit खाता सेटिङहरु, सूचनाहरु र प्राथमिकताहरु व्यवस्थापन गर्नुहोस्।',
            'keywords'    => 'प्रोफाइल, खाता सेटिङ, प्रयोगकर्ता प्रोफाइल',
            'robots'      => 'noindex, nofollow',
        ],

        'vereinswahlen' => [
            'title'       => 'संघ-संस्थाका लागि डिजिटल अनलाइन चुनाव | Public Digit',
            'description' => 'संघ-संस्थाका लागि डिजिटल अनलाइन चुनाव: ✓ गोप्य बोर्ड निर्वाचन ✓ अनलाइन मतदान ✓ हाइब्रिड महासभा ✓ GDPR अनुरूप ✓ एन्ड-टु-एन्ड इन्क्रिप्टेड।',
            'keywords'    => 'संघ-संस्था डिजिटल चुनाव, अनलाइन मतदान, हाइब्रिड महासभा, GDPR मतदान',
            'robots'      => 'index, follow',
        ],

        'hybrid' => [
            'title'       => 'महासभाका लागि हाइब्रिड चुनाव | Public Digit',
            'description' => 'व्यक्तिगत र अनलाइन मतदान संयोजन गर्नुहोस्: स्थानीय र टाढाका सहभागीहरूको साथ मिश्रित महासभाका लागि आदर्श।',
            'keywords'    => 'हाइब्रिड चुनाव, मिश्रित महासभा, रिमोट मतदान, अनलाइन बैठक',
            'robots'      => 'index, follow',
        ],

        'sicherheit' => [
            'title'       => 'एन्ड-टु-एन्ड इन्क्रिप्सनसहित सुरक्षित अनलाइन चुनाव | Public Digit',
            'description' => 'तपाईंको चुनावको लागि बैंक-स्तर सुरक्षा: एन्ड-टु-एन्ड इन्क्रिप्सन, गुमनाम मतदान, छेडछाड-प्रमाण अडिट लगहरू र GDPR अनुपालन।',
            'keywords'    => 'चुनाव सुरक्षा, एन्ड-टु-एन्ड इन्क्रिप्सन, गुमनाम मतदान, GDPR चुनाव',
            'robots'      => 'index, follow',
        ],
    ],
];
