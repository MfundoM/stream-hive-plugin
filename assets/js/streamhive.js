jQuery(document).ready(function ($) {
    // ========== Modal ==========
    const modal = $('#movieModal');
    const modalContent = $('#modalContent');
    const closeModalBtn = $('#closeModalBtn');

    $('[data-movie-id]').on('click', function (e) {
        if ($(e.target).closest('.favorite-btn').length) {
            e.stopPropagation();
            return;
        }

        const movieId = $(this).data('movie-id');
        modalContent.html('<p class="loading-text">Loading...</p>');
        modal.removeClass('hidden');
        $('body').css('overflow', 'hidden');

        $.ajax({
            url: streamhive_ajax.ajax_url,
            method: 'GET',
            data: {
                action: 'streamhive_movie_details',
                id: movieId
            },
            success: function (res) {
                if (!res.success) {
                    modalContent.html('<p style="color: #dc2626; text-align:center;">Failed to load movie details.</p>');
                    return;
                }

                const data = res.data;
                modalContent.html(`
                <h2>${data.movie.title}</h2>
                ${data.trailer_key
                        ? `<iframe style="height: 16rem;" src="https://www.youtube.com/embed/${data.trailer_key}?autoplay=1&mute=1&controls=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>`
                        : `<img src="https://image.tmdb.org/t/p/w500${data.movie.poster_path}" alt="${data.movie.title}" style="max-height: 384px; object-fit: contain;" />`
                    }
                <h5><strong>Release Date:</strong> ${data.movie.release_date}</h5>
                <h5>${data.movie.overview || 'No description available.'}</h5>
            `);
            },
            error: function () {
                modalContent.html('<p style="color: #dc2626; text-align:center;">Something went wrong.</p>');
            }
        });
    });

    closeModalBtn.on('click', function () {
        modal.addClass('hidden');
        modalContent.empty();
        $('body').css('overflow', '');
    });

    modal.on('click', function (e) {
        if (e.target === this) {
            modal.addClass('hidden');
            modalContent.empty();
            $('body').css('overflow', '');
        }
    });

    // ========== Favorites ==========
    $('.favorite-btn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = $(this);
        const movieId = btn.data('movie-id');
        const title = btn.data('title');
        const poster = btn.data('poster');
        const release = btn.data('release');

        $.post(streamhive_ajax.ajax_url, {
            action: 'streamhive_add_to_favorites',
            nonce: streamhive_ajax.nonce,
            movie_id: movieId,
            title: title,
            poster_path: poster,
            release_date: release
        }, function (response) {
            if (response.success) {
                console.log(response.data);
                btn.prop('disabled', true).text('Favorited').css('background-color', '#374151');
            } else {
                console.log('Error adding favorite.');
            }
        });
    });

});
// console.log(streamhive_ajax);

// ========== Autocomplete ==========
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");
    const resultsBox = document.getElementById("autocomplete-results");
    const form = document.getElementById("searchForm");

    if (searchInput && resultsBox && form) {
        searchInput.addEventListener("input", function () {
            const query = this.value.trim();
            if (!query) {
                resultsBox.classList.add("hidden");
                resultsBox.innerHTML = "";
                return;
            }
            // console.log(searchInput);

            fetch(`${streamhive_ajax.search_api_url}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsBox.innerHTML = "";
                    if (!data.results || data.results.length === 0) {
                        resultsBox.classList.add("hidden");
                        return;
                    }

                    data.results.slice(0, 5).forEach(movie => {
                        const li = document.createElement("li");
                        li.textContent = movie.title;
                        li.className = "autocomplete-item";
                        li.addEventListener("click", () => {
                            searchInput.value = movie.title;
                            resultsBox.classList.add("hidden");
                            form.submit();
                        });
                        resultsBox.appendChild(li);
                    });

                    resultsBox.classList.remove("hidden");
                })
                .catch(err => {
                    console.error("Autocomplete fetch error:", err);
                });
        });

        document.addEventListener("click", (e) => {
            if (!form.contains(e.target)) {
                resultsBox.classList.add("hidden");
            }
        });
    }
});

// ========== Nav Toggle ==========
document.getElementById('mobileToggle').addEventListener('click', function () {
    document.getElementById('mobileMenu').classList.toggle('show');
});

