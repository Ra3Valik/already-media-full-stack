export default function initMovies() {
    const loadMoreMoviesBtn = document.getElementById("load-more-movies");
    const movieGrid = document.getElementById("movies-grid");
    const form = document.getElementById("movies-filter-form");
    const orderSelect = document.querySelector('select[name="orderby"]');
    const hiddenInput = document.querySelector('#orderby-hidden');
    const maxPage = parseInt(loadMoreMoviesBtn.dataset.max_pages, 10);
    const originalText = loadMoreMoviesBtn.textContent;
    let isLoading = false;

    orderSelect.addEventListener('change', () => {
        hiddenInput.value = orderSelect.value;
    });

    loadMoreMoviesBtn.addEventListener("click", (e) => {
        e.preventDefault();

        if (isLoading) return;

        isLoading = true;
        loadMoreMoviesBtn.textContent = 'Loading...';
        loadMoreMoviesBtn.disabled = true;

        const currentPage = parseInt(loadMoreMoviesBtn.dataset.page, 10);
        const nextPage = currentPage + 1;

        const filters = getFilterData();
        filters.page = nextPage;
        filters.action = 'load_more_movies';
        filters._ajax_nonce = themeData.nonce;

        fetch(themeData.ajaxurl, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams(filters)
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    movieGrid.insertAdjacentHTML("beforeend", data.data.html);
                    loadMoreMoviesBtn.dataset.page = data.data.current_page;
                    if (data.data.current_page + 1 > maxPage) {
                        loadMoreMoviesBtn.style.display = 'none';
                    }
                } else {
                    console.error('Load error:', data.data);
                }
            })
            .catch((err) => console.error("Load error:", err))
            .finally(() => {
                loadMoreMoviesBtn.textContent = originalText;
                loadMoreMoviesBtn.disabled = false;
                isLoading = false;
            });
    });

    function getFilterData() {
        const formData = new FormData(form);

        const filters = {};
        formData.forEach((value, key) => {
            filters[key] = value;
        });

        return filters;
    }
}
