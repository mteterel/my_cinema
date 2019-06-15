    function getPosterFromStorage(cache, titre)
    {
        if (cache === null)
            return null;

        for(let f of cache['posterCache'])
            if (f.name === titre)
                return f.poster_url;

        return null;
    }

    function updatePosterImage(elem, url)
    {
        let parent = elem.parentNode.parentNode;
        elem.parentNode.remove();
        let img = document.createElement('img');
        img.classList.add("ui");
        img.classList.add("image");
        img.src = url;
        parent.appendChild(img);
    }

    $(document).ready(function() {
        const API_KEY = "15d2ea6d0dc1d476efbca3eba2b9bbfb";

        let posterCache = localStorage.getItem("TMDBPosterCache");
        if (posterCache === null)
            localStorage.setItem("TMDBPosterCache", "{\"posterCache\":[]}");
        posterCacheJSON = JSON.parse(localStorage.getItem("TMDBPosterCache"));
        console.log(posterCacheJSON);

        let elements = $('.movie-poster-placeholder');
        elements.each(function (index) {
            let titre = elements[index].getAttribute('data-movie-title');
            let cachedPoster = getPosterFromStorage(posterCacheJSON, titre);

            if (cachedPoster !== null)
            {
                updatePosterImage(elements[index], cachedPoster);
                console.log("used cached posted for movie : " + titre);
            }
            else
            {
                $.getJSON("https://api.themoviedb.org/3/search/movie?api_key=" + API_KEY + "&query=" + titre + "&callback=?", function (json) {
                    if (json.results.length <= 0)
                    {
                        updatePosterImage(elements[index], "https://via.placeholder.com/500x750?text=?");
                    }
                    else
                    {
                        let url = "http://image.tmdb.org/t/p/w500/" + json.results[0].poster_path;
                        updatePosterImage(elements[index], url);
                        posterCacheJSON['posterCache'].push({
                            'name': titre,
                            'poster_url': url
                        });
                        localStorage.setItem("TMDBPosterCache", JSON.stringify(posterCacheJSON));
                    }
                });
            }
        });
    });