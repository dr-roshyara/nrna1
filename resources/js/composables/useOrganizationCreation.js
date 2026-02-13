import { ref, reactive, computed } from 'vue';

/**
 * Composable for managing the Organization Creation Flow
 * Handles multi-step form state, validation, and navigation
 *
 * Design: Progressive disclosure with educational first, form second
 */
export const useOrganizationCreation = () => {
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
      is_self: false,
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
    formData.representative = { name: '', role: '', email: '', is_self: false };
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
   * Submit the form and create organization
   */
  const submitForm = async () => {
    if (!validateStep(3)) {
      return false;
    }

    // Check if already submitting
    if (isSubmitting.value) {
      return false;
    }

    isSubmitting.value = true;
    submissionError.value = null;

    try {
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
        },
        accept_gdpr: formData.acceptance.gdpr,
        accept_terms: formData.acceptance.terms,
      };

      // POST to web endpoint (not API)
      // Web routes handle session-based auth, API routes don't
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

      if (!csrfToken) {
        throw new Error('CSRF token not found. Please refresh the page.');
      }

      const response = await fetch('/organizations', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin', // For web routes with sessions
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        const error = await response.json();
        console.log('Validation errors:', error.errors);
        // Format error message with specific field errors
        const errorMessages = Object.entries(error.errors || {})
          .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
          .join('\n');
        throw new Error(errorMessages || error.message || 'Failed to create organization');
      }

      const result = await response.json();

      // Track success
      trackOrganizationCreated(payload);

      // Show success message
      if (window.dispatchEvent) {
        window.dispatchEvent(new CustomEvent('show-success', {
          detail: {
            message: result.message || 'Organisation erfolgreich erstellt!',
            title: '✅ Erfolg',
            duration: 5000
          }
        }));
      }

      // Close modal and reset
      closeModal();
      resetForm();

      // Redirect to organization dashboard after 1.5 seconds
      if (result.organization?.redirect_url) {
        setTimeout(() => {
          window.location.href = result.organization.redirect_url;
        }, 1500);
      }

      return result;
    } catch (error) {
      submissionError.value = error.message;
      trackOrganizationCreationError(error);
      return false;
    } finally {
      isSubmitting.value = false;
    }
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
