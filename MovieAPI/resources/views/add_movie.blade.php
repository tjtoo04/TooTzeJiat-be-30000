<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('./resources/css/app.css')
</head>

<title>Add Movie</title>
<body>
    <div class="flex justify-evenly items-center h-screen w-full border border-black">
        <form action="/add_movie" method="POST">
        @csrf
            <div class="p-4 border shadow-sm h-fit w-fit">
                <label for="title">Title:</label>
                <input class="border border-gray-500" id="title" name="title" type="text" placeholder="Title">
            </div>
            <div class="p-4 border shadow-sm h-fit w-fit">
                <label for="release">Release date:</label>
                <input class="border border-gray-500" id="release" name="release" type="date" placeholder="Release Date">
            </div>
            <div class="p-4 border shadow-sm h-fit w-fit">
                <label for="duration">Duration in minutes:</label>
                <input class="border border-gray-500" id="duration" name="duration" type="number" placeholder="Duration">
            </div>
            <div class="p-4 border shadow-sm h-fit w-fit">
                <label for="description">Description:</label>
                <input class="border border-gray-500" id="description" name="description" type="text" placeholder="Description">
            </div>
            <div class="p-4 border shadow-sm h-fit w-fit">
                <label for="mpaa">MPAA Rating:</label>
                <select id="mpaa" name="mpaa_rating" id="mpaa_rating">
                    <option value="G">G</option>
                    <option value="PG">PG</option>
                    <option value="PG-13">PG-13</option>
                    <option value="R">R</option>
                    <option value="NC-17">NC-17</option>
                    <option value="X">X</option>
                    <option value="GP">GP</option>
                    <option value="M">M</option>
                    <option value="M/PG">M/PG</option>
                </select>

            </div>
        
            <div class="p-4 border shadow-sm h-fit w-fit">
                <label for="genre">Genre:</label>
                <input class="border border-gray-500" id="genre" name="genre[]" type="text" placeholder="Genre 1">
                <input class="border border-gray-500" id="genre" name="genre[]" type="text" placeholder="Genre 2 (Optional)">
                <input class="border border-gray-500" id="genre" name="genre[]" type="text" placeholder="Genre 3 (Optional)">
            </div>

            <div class="p-4 border shadow-sm h-fit w-fit">
                <label for="director">Director:</label>
                <input class="border border-gray-500" id="director" name="director" type="text" placeholder="Director">
            </div>

            <div class="p-4 border shadow-sm h-fit w-fit">
                <label for="performer">Performer:</label>
                <input class="border border-gray-500" id="performer" name="performer[]" type="text" placeholder="Performer 1">
                <input class="border border-gray-500" id="performer" name="performer[]" type="text" placeholder="Performer 2">
                <input class="border border-gray-500" id="performer" name="performer[]" type="text" placeholder="Performer 3">
            </div>

            <div class="p-4 border shadow-sm h-fit w-fit">
                <label for="language">Language:</label>
                <select id="language" name="language" id="language">
                    <option value="English">English</option>
                    <option value="Chinese">Chinese</option>
                    <option value="Malay">Malay</option>
                    <option value="Tamil">Tamil</option>
                </select>
            </div>
            <button type="submit">Submit form</button>
        </form>


    </div>
    
</body>
</html>