(() => {
    const normalizeLength = (value) => Array.from(String(value || '').trim()).length;

    const setStatus = (node, state, message) => {
        if (!(node instanceof HTMLElement)) {
            return;
        }

        node.classList.remove('is-green', 'is-yellow', 'is-red');
        node.classList.add(`is-${state}`);

        const text = node.querySelector('.sitio-cero-seo-live__text');
        if (text) {
            text.textContent = message;
        }
    };

    const evaluateTitle = (length) => {
        if (length === 0) {
            return { state: 'red', message: 'Sin titulo SEO. Agrega uno para mejorar relevancia.' };
        }
        if (length < 30) {
            return { state: 'yellow', message: `Titulo corto (${length}). Ideal: 30-60.` };
        }
        if (length <= 60) {
            return { state: 'green', message: `Titulo optimo (${length}).` };
        }
        if (length <= 75) {
            return { state: 'yellow', message: `Titulo algo largo (${length}). Ideal: 30-60.` };
        }

        return { state: 'red', message: `Titulo muy largo (${length}).` };
    };

    const evaluateDescription = (length) => {
        if (length === 0) {
            return { state: 'red', message: 'Sin meta descripcion. Agrega un resumen de 70-160.' };
        }
        if (length < 70) {
            return { state: 'yellow', message: `Descripcion corta (${length}). Ideal: 70-160.` };
        }
        if (length <= 160) {
            return { state: 'green', message: `Descripcion optima (${length}).` };
        }
        if (length <= 220) {
            return { state: 'yellow', message: `Descripcion algo larga (${length}). Ideal: 70-160.` };
        }

        return { state: 'red', message: `Descripcion muy larga (${length}).` };
    };

    const initSeoLive = (root) => {
        const titleInput = root.querySelector('[data-seo-live-input="title"]');
        const descriptionInput = root.querySelector('[data-seo-live-input="description"]');
        const titleStatus = root.querySelector('[data-seo-live-status="title"]');
        const descriptionStatus = root.querySelector('[data-seo-live-status="description"]');

        const updateTitle = () => {
            const result = evaluateTitle(normalizeLength(titleInput ? titleInput.value : ''));
            setStatus(titleStatus, result.state, result.message);
        };

        const updateDescription = () => {
            const result = evaluateDescription(normalizeLength(descriptionInput ? descriptionInput.value : ''));
            setStatus(descriptionStatus, result.state, result.message);
        };

        if (titleInput) {
            titleInput.addEventListener('input', updateTitle);
            titleInput.addEventListener('change', updateTitle);
            updateTitle();
        }

        if (descriptionInput) {
            descriptionInput.addEventListener('input', updateDescription);
            descriptionInput.addEventListener('change', updateDescription);
            updateDescription();
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        const roots = document.querySelectorAll('[data-seo-live-root]');
        roots.forEach((root) => initSeoLive(root));
    });
})();
