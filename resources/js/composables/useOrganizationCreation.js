import { ref, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';

/**
 * Composable for managing the organisation Creation Flow
 * Handles multi-step form state, validation, and navigation
 *
 * Design: Progressive disclosure with educational first, form second
 *
 * IMPORTANT: Uses Inertia router.post() for form submission because:
 * 1. This is an Inertia application (Inertia 2.0)
 * 2. Inertia automatically handles CSRF tokens via meta tags
 * 3. Inertia sets correct headers (X-Requested-With, Accept)
 * 4. Inertia properly handles redirects and flash messages
 */
export const useOrganizationCreation = () => {
  // No need for manual CSRF handling - Inertia handles it
  // Step tracking (0 = education overlay, 1-3 = form steps)
  const currentStep = ref(0);
  const isModalOpen = ref(false);
  const showEducation = ref(true);

  // Form data collected progressively
  const formData = reactive({
    basic: {
      name: '',
      email: '',
    },
    address: {
      street: '',
      city: '',
      zip: '',
      country: 'DE',
    },
    representative: {
      name: '',
      role: '',
      email: '',
      is_self: true,
    },
    acceptance: {
      gdpr: false,
      terms: false,
    },
  });

  // Expanded sections in education overlay
  const expandedSections = reactive({
    dataPrivacy: false,
    requirements: false,
  });

  // Validation state per step
  const validationErrors = reactive({
    basic: {},
    address: {},
    representative: {},
  });

  // Loading state for submission
  const isSubmitting = ref(false);
  const submissionError = ref(null);

  // Step titles for progress indicator
  const stepTitles = {
    0: 'Konzept',
    1: 'Grunddaten',
    2: 'Adresse',
    3: 'Vertreter',
  };

  /**
   * Open the modal and show education overlay
   */
  const openModal = () => {
    isModalOpen.value = true;
    currentStep.value = 0;
    showEducation.value = true;
    submissionError.value = null;
  };

  /**
   * Close the modal and reset state
   */
  const closeModal = () => {
    isModalOpen.value = false;
    resetForm();
  };

  /**
   * Reset all form data
   */
  const resetForm = () => {
    formData.basic = { name: '', email: '' };
    formData.address = { street: '', city: '', zip: '', country: 'DE' };
    formData.representative = { name: '', role: '', email: '', is_self: true };
    formData.acceptance = { gdpr: false, terms: false };
    validationErrors.basic = {};
    validationErrors.address = {};
    validationErrors.representative = {};
    currentStep.value = 0;
    showEducation.value = true;
    isSubmitting.value = false;
    submissionError.value = null;
  };

  /**
   * Move to next step after validation
   */
  const nextStep = () => {
    if (currentStep.value === 0 && showEducation.value) {
      // From education overlay to form step 1
      showEducation.value = false;
      currentStep.value = 1;
      return true;
    }

    if (validateStep(currentStep.value)) {
      if (currentStep.value < 3) {
        currentStep.value++;
        return true;
      }
    }
    return false;
  };

  /**
   * Move to previous step
   */
  const previousStep = () => {
    if (currentStep.value > 1) {
      currentStep.value--;
      return true;
    }
    if (currentStep.value === 1) {
      // Back to education
      showEducation.value = true;
      currentStep.value = 0;
      return true;
    }
    return false;
  };

  /**
   * Validate current step
   */
  const validateStep = (step) => {
    validationErrors[getStepKey(step)] = {};
    const errors = validationErrors[getStepKey(step)];

    switch (step) {
      case 1: // Basic info
        if (!formData.basic.name?.trim()) {
          errors.name = 'Organisationname ist erforderlich';
        }
        if (!formData.basic.email?.trim()) {
          errors.email = 'E-Mail-Adresse ist erforderlich';
        } else if (!isValidEmail(formData.basic.email)) {
          errors.email = 'Ungültige E-Mail-Adresse';
        }
        break;

      case 2: // Address
        if (!formData.address.street?.trim()) {
          errors.street = 'Straße und Hausnummer erforderlich';
        }
        if (!formData.address.city?.trim()) {
          errors.city = 'Ort ist erforderlich';
        }
        if (!formData.address.zip?.trim()) {
          errors.zip = 'Postleitzahl ist erforderlich';
        } else if (!isValidGermanZip(formData.address.zip)) {
          errors.zip = 'Ungültige deutsche Postleitzahl';
        }
        break;

      case 3: // Representative
        if (!formData.representative.name?.trim()) {
          errors.name = 'Name erforderlich';
        }
        if (!formData.representative.role?.trim()) {
          errors.role = 'Funktion erforderlich';
        }
        // Email is only required if NOT self-representative
        if (!formData.representative.is_self) {
          if (!formData.representative.email?.trim()) {
            errors.email = 'E-Mail-Adresse erforderlich';
          } else if (!isValidEmail(formData.representative.email)) {
            errors.email = 'Ungültige E-Mail-Adresse';
          }
        }
        if (!formData.acceptance.gdpr) {
          errors.gdpr = 'DSGVO-Zustimmung erforderlich';
        }
        if (!formData.acceptance.terms) {
          errors.terms = 'Nutzungsbedingungen erforderlich';
        }
        break;
    }

    return Object.keys(errors).length === 0;
  };

  /**
   * Get step key for error tracking
   */
  const getStepKey = (step) => {
    const keys = { 1: 'basic', 2: 'address', 3: 'representative' };
    return keys[step] || 'basic';
  };

  /**
   * Check if email is valid
   */
  const isValidEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  };

  /**
   * Check if German ZIP code is valid (5 digits)
   */
  const isValidGermanZip = (zip) => {
    return /^\d{5}$/.test(zip);
  };

  /**
   * Submit the form and create organisation
   *
   * Uses Inertia 2.0 router.post() for proper form submission handling.
   * Inertia automatically manages CSRF tokens and HTTP headers.
   */
  const submitForm = () => {
    if (!validateStep(3)) {
      return false;
    }

    // Check if already submitting
    if (isSubmitting.value) {
      return false;
    }

    isSubmitting.value = true;
    submissionError.value = null;

    // Build payload
    const payload = {
      name: formData.basic.name.trim(),
      email: formData.basic.email.trim().toLowerCase(),
      address: {
        street: formData.address.street.trim(),
        city: formData.address.city.trim(),
        zip: formData.address.zip.trim(),
        country: formData.address.country,
      },
      representative: {
        name: formData.representative.name.trim(),
        role: formData.representative.role.trim(),
        email: formData.representative.email?.trim() || formData.basic.email.trim(),
        is_self: formData.representative.is_self,
      },
      accept_gdpr: formData.acceptance.gdpr,
      accept_terms: formData.acceptance.terms,
    };

    // Get route URL from Laravel's route system
    const routeUrl = route('organizations.store');

    console.log('📤 Sending organisation creation request via Inertia router', {
      url: routeUrl,
      payload: payload,
    });

    // Use Inertia router for form submission
    // Inertia handles CSRF tokens automatically via meta tags
    // and sets proper headers (X-Requested-With, Accept)
    router.post(routeUrl, payload, {
      preserveState: true,
      preserveScroll: true,
      onSuccess: (page) => {
        console.log('✅ organisation created successfully!', page.props);

        // Track success
        trackOrganizationCreated(payload);

        // Close modal and reset
        closeModal();
        resetForm();

        // Show success message
        if (window.dispatchEvent) {
          window.dispatchEvent(new CustomEvent('show-success', {
            detail: {
              message: page.props.flash?.success || 'Organisation erfolgreich erstellt!',
              title: '✅ Erfolg',
              duration: 5000
            }
          }));
        }

        isSubmitting.value = false;
      },
      onError: (errors) => {
        console.error('organisation creation error:', errors);

        // Format error messages from validation
        let errorMessage = 'Bitte überprüfen Sie Ihre Eingaben';

        if (errors.message) {
          errorMessage = errors.message;
        } else if (typeof errors === 'object') {
          const errorMessages = Object.entries(errors)
            .map(([field, messages]) => {
              const msg = Array.isArray(messages) ? messages.join(', ') : messages;
              return `${field}: ${msg}`;
            })
            .filter(msg => msg.trim())
            .join('\n');

          if (errorMessages) {
            errorMessage = errorMessages;
          }
        }

        submissionError.value = errorMessage;
        trackOrganizationCreationError(errors);
        isSubmitting.value = false;
      },
      onFinish: () => {
        isSubmitting.value = false;
      }
    });
  };

  /**
   * Toggle expandable section in education
   */
  const toggleSection = (section) => {
    expandedSections[section] = !expandedSections[section];
    trackEducationSectionViewed(section);
  };

  /**
   * Track analytics events
   */
  const trackOrganizationCreationStarted = () => {
    if (window.gtag) {
      window.gtag('event', 'organization_creation_started', {
        event_category: 'onboarding',
      });
    }
  };

  const trackEducationSectionViewed = (section) => {
    if (window.gtag) {
      window.gtag('event', 'organization_education_viewed', {
        event_category: 'onboarding',
        section,
      });
    }
  };

  const trackStepCompleted = (step) => {
    if (window.gtag) {
      window.gtag('event', 'organization_step_completed', {
        event_category: 'onboarding',
        step: stepTitles[step],
      });
    }
  };

  const trackOrganizationCreated = (data) => {
    if (window.gtag) {
      window.gtag('event', 'organization_created', {
        event_category: 'onboarding',
        organization_name: data.name,
      });
    }
  };

  const trackOrganizationCreationError = (error) => {
    if (window.gtag) {
      window.gtag('event', 'organization_creation_error', {
        event_category: 'onboarding',
        error_message: error.message,
      });
    }
  };

  // Computed properties
  const isFormStep = computed(() => currentStep.value > 0 && !showEducation.value);
  const canGoNext = computed(() => {
    if (showEducation.value) return true;
    return validateStep(currentStep.value);
  });
  const canGoPrevious = computed(() => currentStep.value > 0);
  const progressPercentage = computed(() => {
    if (showEducation.value) return 0;
    return (currentStep.value / 3) * 100;
  });

  return {
    // State
    currentStep,
    isModalOpen,
    showEducation,
    formData,
    expandedSections,
    validationErrors,
    isSubmitting,
    submissionError,
    stepTitles,

    // Methods
    openModal,
    closeModal,
    resetForm,
    nextStep,
    previousStep,
    validateStep,
    submitForm,
    toggleSection,
    trackOrganizationCreationStarted,
    trackStepCompleted,

    // Computed
    isFormStep,
    canGoNext,
    canGoPrevious,
    progressPercentage,
  };
};
