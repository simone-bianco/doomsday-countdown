import { loadValidationMessages, setValidationLocale } from '@simone-bianco/vue-form-core';
import { validationMessages } from '@/generated/validation-messages';
import type { SupportedLocale } from './index';

export function initializeValidationLocale(locale: SupportedLocale): void {
    loadValidationMessages(validationMessages);
    setValidationLocale(locale);
}
