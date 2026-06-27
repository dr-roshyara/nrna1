export function useLocaleDebug() {
    const printDebugInfo = () => {
        console.group('🌍 Locale Detection Debug Info');
        console.log('Browser Timezone:', Intl.DateTimeFormat().resolvedOptions().timeZone);
        console.log('localStorage preferred_locale:', localStorage.getItem('preferred_locale'));
        console.log('Cookie locale:', document.cookie.match(/locale=([^;]+)/)?.[1] || '(not set)');
        console.log('Accept-Language:', navigator.language || '(not available)');
        console.groupEnd();
    };

    const testGeoDetectionApi = async () => {
        console.log('Testing /api/detect-location endpoint...');
        try {
            const response = await fetch('/api/detect-location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
                })
            });
            const data = await response.json();
            console.group('API Response');
            console.log('Status:', response.status);
            console.log('Detected Locale:', data.locale);
            console.log('Decision Source:', data.decision?.source);
            console.log('Full Response:', data);
            console.groupEnd();
            return data;
        } catch (error) {
            console.error('API test failed:', error);
        }
    };

    const clearLocalePreferences = () => {
        console.log('Clearing all locale preferences...');
        localStorage.removeItem('preferred_locale');
        document.cookie = 'locale=; max-age=0; path=/';
        console.log('Preferences cleared. Reload page to re-detect.');
    };

    // Expose debug utilities on window if in development
    if (import.meta.env.DEV) {
        window.__localeDebug = {
            print: printDebugInfo,
            testApi: testGeoDetectionApi,
            clear: clearLocalePreferences,
            info: () => {
                printDebugInfo();
                return testGeoDetectionApi();
            }
        };
        console.log('✅ Locale debugging available: window.__localeDebug.info()');
    }

    return {
        printDebugInfo,
        testGeoDetectionApi,
        clearLocalePreferences
    };
}
