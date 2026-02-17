(() => {
    const initNavigation = () => {
        const nav = document.querySelector('.site-nav');
        const navToggle = document.querySelector('.nav-toggle');

        if (!nav) {
            return;
        }

        const navLinks = Array.from(nav.querySelectorAll('.site-nav__list a[href]'));

        const closeMenu = () => {
            if (!navToggle) {
                return;
            }
            nav.classList.remove('is-open');
            navToggle.setAttribute('aria-expanded', 'false');
        };

        if (navToggle) {
            navToggle.addEventListener('click', () => {
                const isOpen = nav.classList.toggle('is-open');
                navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }

        navLinks.forEach((link) => {
            link.addEventListener('click', closeMenu);
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 860) {
                closeMenu();
            }
        });

        const normalizePath = (path) => {
            const cleanPath = String(path || '').replace(/\/+$/, '');
            return cleanPath === '' ? '/' : cleanPath;
        };

        const decodeHashId = (hash) => {
            const raw = String(hash || '').replace(/^#/, '').trim();
            if (raw === '') {
                return '';
            }

            try {
                return decodeURIComponent(raw);
            } catch (_error) {
                return raw;
            }
        };

        const currentUrl = new URL(window.location.href);
        const currentOrigin = currentUrl.origin;
        const currentPath = normalizePath(currentUrl.pathname);
        const currentSearch = String(currentUrl.search || '');

        const sectionEntries = [];

        navLinks.forEach((link) => {
            const href = String(link.getAttribute('href') || '').trim();
            if (href === '') {
                return;
            }

            let parsedUrl = null;
            try {
                parsedUrl = new URL(href, currentOrigin);
            } catch (_error) {
                return;
            }

            if (parsedUrl.origin !== currentOrigin) {
                return;
            }

            const targetPath = normalizePath(parsedUrl.pathname);
            const targetSearch = String(parsedUrl.search || '');
            const isSameDocument = targetPath === currentPath && targetSearch === currentSearch;

            if (!isSameDocument) {
                return;
            }

            const hashId = decodeHashId(parsedUrl.hash);
            const menuItem = link.closest('li');

            if (hashId === '') {
                return;
            }

            const section = document.getElementById(hashId);
            if (!section) {
                return;
            }

            sectionEntries.push({
                id: hashId,
                link,
                menuItem,
                section
            });
        });

        if (sectionEntries.length === 0) {
            return;
        }

        nav.classList.add('has-scrollspy');

        const setActiveLink = (activeLink) => {
            navLinks.forEach((link) => {
                const isActive = link === activeLink;
                const menuItem = link.closest('li');

                link.classList.toggle('is-active', isActive);
                if (menuItem) {
                    menuItem.classList.toggle('is-active', isActive);
                }

                if (isActive) {
                    link.setAttribute('data-scrollspy-current', '1');
                    link.setAttribute('aria-current', 'page');
                    return;
                }

                if (link.getAttribute('data-scrollspy-current') === '1') {
                    link.removeAttribute('data-scrollspy-current');
                    link.removeAttribute('aria-current');
                }
            });
        };

        const resolveActiveEntryByViewport = () => {
            const header = document.querySelector('.site-header');
            const headerOffset = header ? header.getBoundingClientRect().height : 0;
            const scanLine = window.scrollY + headerOffset + 20;

            const positionedEntries = sectionEntries
                .map((entry) => {
                    const rect = entry.section.getBoundingClientRect();
                    const top = rect.top + window.scrollY;

                    return {
                        entry,
                        top,
                        bottom: top + Math.max(rect.height, 1)
                    };
                })
                .sort((a, b) => a.top - b.top);

            for (let index = 0; index < positionedEntries.length; index += 1) {
                const current = positionedEntries[index];
                if (scanLine >= current.top && scanLine < current.bottom) {
                    return current.entry;
                }
            }

            return null;
        };

        const syncActiveState = () => {
            const activeEntry = resolveActiveEntryByViewport();
            setActiveLink(activeEntry ? activeEntry.link : null);
        };

        let pending = false;
        const scheduleSync = () => {
            if (pending) {
                return;
            }

            pending = true;
            window.requestAnimationFrame(() => {
                pending = false;
                syncActiveState();
            });
        };

        window.addEventListener('scroll', scheduleSync, { passive: true });
        window.addEventListener('resize', scheduleSync);
        window.addEventListener('hashchange', scheduleSync);
        window.addEventListener('load', scheduleSync, { once: true });

        scheduleSync();
    };

    const initHeroSlider = (slider) => {
        const slides = Array.from(slider.querySelectorAll('[data-slide]'));
        const dots = Array.from(slider.querySelectorAll('[data-slide-dot]'));
        const prevButton = slider.querySelector('[data-slide-prev]');
        const nextButton = slider.querySelector('[data-slide-next]');

        if (slides.length === 0) {
            return;
        }

        let currentIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));
        if (currentIndex < 0) {
            currentIndex = 0;
        }

        const setSlide = (index) => {
            const normalizedIndex = (index + slides.length) % slides.length;
            currentIndex = normalizedIndex;

            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === normalizedIndex;
                slide.classList.toggle('is-active', isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            dots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === normalizedIndex;
                dot.classList.toggle('is-active', isActive);
                dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
        };

        setSlide(currentIndex);

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                setSlide(currentIndex - 1);
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                setSlide(currentIndex + 1);
            });
        }

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                const targetIndex = Number.parseInt(dot.dataset.slideDot || '0', 10);
                if (!Number.isNaN(targetIndex)) {
                    setSlide(targetIndex);
                }
            });
        });

        if (slider.dataset.autoplay !== 'true' || slides.length < 2) {
            return;
        }

        let autoplayTimer = null;

        const stopAutoplay = () => {
            if (autoplayTimer) {
                window.clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        const startAutoplay = () => {
            stopAutoplay();
            autoplayTimer = window.setInterval(() => {
                setSlide(currentIndex + 1);
            }, 6000);
        };

        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);
        slider.addEventListener('focusin', stopAutoplay);
        slider.addEventListener('focusout', (event) => {
            if (!slider.contains(event.relatedTarget)) {
                startAutoplay();
            }
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoplay();
            } else {
                startAutoplay();
            }
        });

        startAutoplay();
    };

    const initHeroSliders = () => {
        const sliders = document.querySelectorAll('[data-hero-slider]');
        sliders.forEach((slider) => initHeroSlider(slider));
    };

    const initAvisosCarousel = (carousel) => {
        const viewport = carousel.querySelector('[data-avisos-viewport]');
        const track = carousel.querySelector('[data-avisos-track]');
        const cards = Array.from(carousel.querySelectorAll('[data-aviso-card]'));
        const prevButton = carousel.querySelector('[data-avisos-prev]');
        const nextButton = carousel.querySelector('[data-avisos-next]');

        if (!viewport || !track || cards.length === 0) {
            return;
        }

        let currentPage = 0;
        let maxPage = 0;
        let autoplayTimer = null;

        const getPerView = () => {
            const rawValue = window.getComputedStyle(carousel).getPropertyValue('--avisos-per-view');
            const parsed = Number.parseInt(rawValue || '5', 10);
            if (Number.isNaN(parsed) || parsed <= 0) {
                return 5;
            }
            return parsed;
        };

        const updateControls = () => {
            const isStatic = maxPage <= 0;
            if (prevButton) {
                prevButton.disabled = isStatic;
            }
            if (nextButton) {
                nextButton.disabled = isStatic;
            }
        };

        const applyTransform = () => {
            const x = currentPage * viewport.clientWidth;
            track.style.transform = `translateX(-${x}px)`;
        };

        const refresh = () => {
            const perView = getPerView();
            maxPage = Math.max(Math.ceil(cards.length / perView) - 1, 0);
            if (currentPage > maxPage) {
                currentPage = maxPage;
            }
            applyTransform();
            updateControls();
        };

        const goToPage = (page) => {
            if (maxPage <= 0) {
                return;
            }
            const totalPages = maxPage + 1;
            currentPage = (page + totalPages) % totalPages;
            applyTransform();
            updateControls();
        };

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                goToPage(currentPage - 1);
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                goToPage(currentPage + 1);
            });
        }

        const stopAutoplay = () => {
            if (autoplayTimer) {
                window.clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        const startAutoplay = () => {
            stopAutoplay();
            if (maxPage <= 0) {
                return;
            }
            autoplayTimer = window.setInterval(() => {
                goToPage(currentPage + 1);
            }, 5200);
        };

        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);
        carousel.addEventListener('focusin', stopAutoplay);
        carousel.addEventListener('focusout', (event) => {
            if (!carousel.contains(event.relatedTarget)) {
                startAutoplay();
            }
        });

        window.addEventListener('resize', () => {
            refresh();
        });

        refresh();
        startAutoplay();
    };

    const initAvisosCarousels = () => {
        const carousels = document.querySelectorAll('[data-avisos-carousel]');
        carousels.forEach((carousel) => initAvisosCarousel(carousel));
    };

    const initNewsGallery = (gallery) => {
        const slides = Array.from(gallery.querySelectorAll('[data-news-gallery-slide]'));
        if (slides.length === 0) {
            return;
        }

        const thumbs = Array.from(gallery.querySelectorAll('[data-news-gallery-thumb]'));
        const prevButtons = Array.from(gallery.querySelectorAll('[data-news-gallery-prev]'));
        const nextButtons = Array.from(gallery.querySelectorAll('[data-news-gallery-next]'));
        const openButtons = Array.from(gallery.querySelectorAll('[data-news-gallery-open]'));
        const closeButtons = Array.from(gallery.querySelectorAll('[data-news-gallery-close]'));
        const lightbox = gallery.querySelector('[data-news-gallery-lightbox]');
        const lightboxImage = gallery.querySelector('[data-news-gallery-lightbox-image]');

        let currentIndex = 0;

        const setSlide = (index) => {
            const normalizedIndex = (index + slides.length) % slides.length;
            currentIndex = normalizedIndex;

            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === normalizedIndex;
                slide.classList.toggle('is-active', isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            thumbs.forEach((thumb, thumbIndex) => {
                const isActive = thumbIndex === normalizedIndex;
                thumb.classList.toggle('is-active', isActive);
                thumb.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            if (lightbox && lightboxImage) {
                const activeImage = slides[normalizedIndex].querySelector('.single-noticia-gallery__image');
                if (activeImage) {
                    const fullSrc = activeImage.getAttribute('data-full-src') || activeImage.getAttribute('src') || '';
                    const altText = activeImage.getAttribute('alt') || '';
                    lightboxImage.setAttribute('src', fullSrc);
                    lightboxImage.setAttribute('alt', altText);
                }
            }
        };

        const toggleLightbox = (isOpen) => {
            if (!lightbox) {
                return;
            }

            if (isOpen) {
                lightbox.removeAttribute('hidden');
                lightbox.setAttribute('aria-hidden', 'false');
                document.body.classList.add('has-news-gallery-lightbox');
            } else {
                lightbox.setAttribute('hidden', 'hidden');
                lightbox.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('has-news-gallery-lightbox');
            }
        };

        prevButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setSlide(currentIndex - 1);
            });
        });

        nextButtons.forEach((button) => {
            button.addEventListener('click', () => {
                setSlide(currentIndex + 1);
            });
        });

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', () => {
                const index = Number.parseInt(thumb.dataset.index || '0', 10);
                if (!Number.isNaN(index)) {
                    setSlide(index);
                }
            });
        });

        openButtons.forEach((button) => {
            button.addEventListener('click', () => {
                toggleLightbox(true);
            });
        });

        closeButtons.forEach((button) => {
            button.addEventListener('click', () => {
                toggleLightbox(false);
            });
        });

        if (lightbox) {
            lightbox.addEventListener('click', (event) => {
                const target = event.target;
                if (!(target instanceof HTMLElement)) {
                    return;
                }

                if (target === lightbox) {
                    toggleLightbox(false);
                }
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                toggleLightbox(false);
            }

            if (event.key === 'ArrowLeft') {
                const isLightboxOpen = lightbox && !lightbox.hasAttribute('hidden');
                if (isLightboxOpen) {
                    event.preventDefault();
                    setSlide(currentIndex - 1);
                }
            }

            if (event.key === 'ArrowRight') {
                const isLightboxOpen = lightbox && !lightbox.hasAttribute('hidden');
                if (isLightboxOpen) {
                    event.preventDefault();
                    setSlide(currentIndex + 1);
                }
            }
        });

        setSlide(0);
    };

    const initNewsGalleries = () => {
        const galleries = document.querySelectorAll('[data-news-gallery]');
        galleries.forEach((gallery) => initNewsGallery(gallery));
    };

    const initDireccionAccordion = (accordion) => {
        const items = Array.from(accordion.querySelectorAll('[data-direccion-accordion-item]'));
        if (items.length === 0) {
            return;
        }

        items.forEach((item, index) => {
            const button = item.querySelector('[data-direccion-accordion-toggle]');
            const panel = item.querySelector('[data-direccion-accordion-panel]');
            if (!button || !panel) {
                return;
            }

            const isInitiallyOpen = index === 0 && !panel.hasAttribute('hidden');
            button.setAttribute('aria-expanded', isInitiallyOpen ? 'true' : 'false');
            button.classList.toggle('is-active', isInitiallyOpen);

            button.addEventListener('click', () => {
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                const nextState = !isExpanded;
                button.setAttribute('aria-expanded', nextState ? 'true' : 'false');
                button.classList.toggle('is-active', nextState);

                if (nextState) {
                    panel.removeAttribute('hidden');
                } else {
                    panel.setAttribute('hidden', 'hidden');
                }
            });
        });
    };

    const initDireccionSubtabs = (subtabsRoot) => {
        const items = Array.from(subtabsRoot.querySelectorAll('[data-direccion-subtab-item]'));
        if (items.length === 0) {
            return;
        }

        const setItemState = (button, panel, shouldOpen) => {
            button.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
            button.classList.toggle('elementor-active', shouldOpen);

            if (shouldOpen) {
                panel.style.display = 'block';
            } else {
                panel.style.display = 'none';
            }
        };

        const entries = items
            .map((item) => {
                const button = item.querySelector('[data-direccion-subtab-toggle]');
                const panel = item.querySelector('[data-direccion-subtab-panel]');
                if (!button || !panel) {
                    return null;
                }
                return { button, panel };
            })
            .filter(Boolean);

        entries.forEach((entry) => {
            const { button, panel } = entry;
            const isInitiallyOpen = button.getAttribute('aria-expanded') === 'true' || button.classList.contains('elementor-active');
            setItemState(button, panel, isInitiallyOpen);
        });

        entries.forEach((entry) => {
            const { button, panel } = entry;
            const closeOthers = () => {
                entries.forEach((otherEntry) => {
                    if (otherEntry.button !== button) {
                        setItemState(otherEntry.button, otherEntry.panel, false);
                    }
                });
            };

            const toggle = () => {
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                if (isExpanded) {
                    setItemState(button, panel, false);
                    return;
                }
                closeOthers();
                setItemState(button, panel, true);
            };

            button.addEventListener('click', () => {
                toggle();
            });

            button.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggle();
                }
            });
        });
    };

    const initDireccionAccordions = () => {
        const accordions = document.querySelectorAll('[data-direccion-accordion]');
        accordions.forEach((accordion) => initDireccionAccordion(accordion));

        const subtabs = document.querySelectorAll('[data-direccion-subtabs]');
        subtabs.forEach((subtabsRoot) => initDireccionSubtabs(subtabsRoot));
    };

    const initShortcodeAccordions = () => {
        const accordions = document.querySelectorAll('[data-sc-accordion]');
        if (accordions.length === 0) {
            return;
        }

        accordions.forEach((accordion) => {
            const headers = Array.from(accordion.querySelectorAll('.sc-accordion-header'));
            headers.forEach((button) => {
                const content = button.nextElementSibling;
                if (!(content instanceof HTMLElement)) {
                    return;
                }

                button.setAttribute('aria-expanded', 'false');
                content.style.maxHeight = '';
                content.setAttribute('hidden', 'hidden');
                button.classList.remove('active');

                button.addEventListener('click', () => {
                    const isOpen = button.classList.contains('active');

                    if (isOpen) {
                        button.classList.remove('active');
                        button.setAttribute('aria-expanded', 'false');
                        content.style.maxHeight = '';
                        window.setTimeout(() => {
                            if (!button.classList.contains('active')) {
                                content.setAttribute('hidden', 'hidden');
                            }
                        }, 300);
                        return;
                    }

                    button.classList.add('active');
                    button.setAttribute('aria-expanded', 'true');
                    content.removeAttribute('hidden');
                    content.style.maxHeight = `${content.scrollHeight}px`;
                });
            });
        });
    };

    initNavigation();
    initHeroSliders();
    initAvisosCarousels();
    initNewsGalleries();
    initDireccionAccordions();
    initShortcodeAccordions();
})();
