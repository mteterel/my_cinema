$(document).ready(function() {
    const API_KEY = "15d2ea6d0dc1d476efbca3eba2b9bbfb";

    let elements = $('.movie-poster-placeholder');
    elements.each(function(index) {
        let titre = elements[index].getAttribute('data-movie-title');
        $.getJSON("https://api.themoviedb.org/3/search/movie?api_key=" + API_KEY + "&query=" + titre + "&callback=?", function(json) {
            if (json === "Nothing found.")
                alert("poster not found " + titre);
            else
            {
                let url = "http://image.tmdb.org/t/p/w500/" + json.results[0].poster_path;
                let parent = elements[index].parentNode.parentNode;
                elements[index].parentNode.remove();
                let img = document.createElement('img');
                img.classList.add("ui");
                img.classList.add("image");
                img.src = url;
                parent.appendChild(img);
            }
        });
    });
});