$(function(){
	function loadMovieDetail(imdb_id){
		$("#movie_detail_holder").load("http://localhost/movies/movies/parse/" + imdb_id);
	}	
})
