SELECT * FROM `movies` WHERE year=1995;

SELECT COUNT(*) FROM movies
JOIN roles on movies.id=roles.movie_id
WHERE movies.name='Lost in Translation';

SELECT a.first_name,a.last_name FROM movies
JOIN roles r on movies.id=r.movie_id
JOIN actors a on r.actor_id=a.id
WHERE movies.name='Lost in Translation';

SELECT d.first_name,d.last_name FROM movies m
JOIN movies_directors md on m.id=md.movie_id
JOIN directors d on md.director_id=d.id
WHERE m.name='Fight Club';