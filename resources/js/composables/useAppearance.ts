import { onMounted, ref } from 'vue';

type Appearance = 'light' | 'dark' | 'system';

const getCookie = (name: string): string | null => {
    if (typeof document === 'undefined') return null;

    const match = document.cookie
        .split(';')
        .map((c) => c.trim())
        .find((c) => c.startsWith(`${name}=`));

    if (!match) return null;

    const value = match.substring(name.length + 1);
    return decodeURIComponent(value);
};

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') return;

    const maxAge = days * 24 * 60 * 60;
    document.cookie = `${name}=${encodeURIComponent(value)};path=/;max-age=${maxAge};SameSite=Lax`;
};

const mediaQuery = () => {
    if (typeof window === 'undefined') return null;
    return window.matchMedia('(prefers-color-scheme: dark)');
};

export function updateTheme(value: Appearance) {
    if (typeof window === 'undefined') return;

    if (value === 'system') {
        const systemTheme = mediaQuery()?.matches ? 'dark' : 'light';
        document.documentElement.classList.toggle('dark', systemTheme === 'dark');
        return;
    }

    document.documentElement.classList.toggle('dark', value === 'dark');
}

const getStoredAppearance = (): Appearance | null => {
    if (typeof window === 'undefined') return null;

    const fromCookie = getCookie('appearance') as Appearance | null;
    if (fromCookie === 'light' || fromCookie === 'dark' || fromCookie === 'system') {
        return fromCookie;
    }

    const fromLocalStorage = localStorage.getItem('appearance') as Appearance | null;
    if (fromLocalStorage === 'light' || fromLocalStorage === 'dark' || fromLocalStorage === 'system') {
        return fromLocalStorage;
    }

    return null;
};

const handleSystemThemeChange = () => {
    const current = getStoredAppearance() || 'system';
    updateTheme(current);

    if (!getCookie('appearance')) {
        setCookie('appearance', current);
    }
};

export function initializeTheme() {
    if (typeof window === 'undefined') return;

    const current = getStoredAppearance() || 'system';
    updateTheme(current);

    if (!getCookie('appearance')) {
        setCookie('appearance', current);
    }

    mediaQuery()?.addEventListener('change', handleSystemThemeChange);
}

const appearance = ref<Appearance>('system');

export function useAppearance() {
    onMounted(() => {
        const saved = getStoredAppearance();
        if (saved) appearance.value = saved;

        if (!getCookie('appearance')) {
            setCookie('appearance', appearance.value);
        }
    });

    function updateAppearance(value: Appearance) {
        appearance.value = value;
        setCookie('appearance', value);
        localStorage.setItem('appearance', value);
        updateTheme(value);
    }

    return { appearance, updateAppearance };
}