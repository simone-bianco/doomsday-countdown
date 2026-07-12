import { AsyncLocalStorage } from 'node:async_hooks';
import { createI18nInstance, registerServerI18nResolver } from './index';

const requestI18n = new AsyncLocalStorage();

registerServerI18nResolver(() => requestI18n.getStore());

export async function withServerI18n(locale, callback) {
    const instance = await createI18nInstance(locale);

    return requestI18n.run(instance, callback);
}
