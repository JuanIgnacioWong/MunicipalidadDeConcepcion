(() => {
    const initNavigation = () => {
        const navToggle = document.querySelector('.nav-toggle');
        const nav = document.querySelector('.site-nav');

        if (!navToggle || !nav) {
            return;
        }

        const closeMenu = () => {
            nav.classList.remove('is-open');
            navToggle.setAttribute('aria-expanded', 'false');
        };

        navToggle.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        nav.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeMenu);
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 860) {
                closeMenu();
            }
        });
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

    initNavigation();
    initHeroSliders();
    initAvisosCarousels();
    initNewsGalleries();
    initDireccionAccordions();
})();
