const movies = [
  { title: 'The Passengers', genre: 'Drama', rating: 9.3, year: 1994 },
  { title: 'The Godfather', genre: 'Crime', rating: 9.2, year: 1972 },
  { title: 'The Dark Knight', genre: 'Action', rating: 9.0, year: 2008 },
];

function filterMovies(genre, rating, year) {
  return movies.filter(movie => {
    return (
      (!genre || movie.genre.toLowerCase().includes(genre.toLowerCase())) &&
      (!rating || movie.rating >= rating) &&
      (!year || movie.year === parseInt(year)) // Ensure year is compared as a number
    );
  });
}

const form = document.querySelector('form');
const genreInput = document.querySelector('#genre');
const ratingInput = document.querySelector('#rating');
const yearInput = document.querySelector('#year');
const recommendationsSection = document.querySelector('#recommendations');

form.addEventListener('submit', function(event) {
  event.preventDefault(); 

  const genre = genreInput.value;
  const rating = parseFloat(ratingInput.value); // Convert string to number
  const year = yearInput.value;

  const filteredMovies = filterMovies(genre, rating, year);

  // MOVE THE DISPLAY LOGIC INSIDE THE LISTENER
  displayMovies(filteredMovies); 
});

// Wrap the display logic in a function for better organization
function displayMovies(moviesList) {
  // Clear previous recommendations
  recommendationsSection.innerHTML = '<h2>Recommended Movies</h2>';

  if (moviesList.length === 0) {
    recommendationsSection.innerHTML += '<p>No movies found matching those criteria.</p>';
    return;
  }

  // Loop through the filtered movies and display them
  moviesList.forEach(movie => {
    const movieCard = document.createElement('div');
    movieCard.classList.add('movie-card');

    const title = document.createElement('h3');
    title.textContent = movie.title;

    const details = document.createElement('p');
    details.textContent = `Genre: ${movie.genre} | Rating: ${movie.rating} | Year: ${movie.year}`;

    movieCard.appendChild(title);
    movieCard.appendChild(details);
    recommendationsSection.appendChild(movieCard);
  });
}