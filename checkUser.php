<?php
require "./db.php";
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Géoréférencée</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style src="style.css"></style>
</head>

<body>
    <form class="max-w-sm mx-auto mt-8" >
        <div class="mb-5">
            <label for="Num_tit" class="block mb-2 text-sm font-medium text-gray-900 ">Your Num_tit</label>
            <input type="text" id="Num_tit"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                placeholder="x122344556" required />
        </div>
        <div class="mb-5">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Your password</label>
            <input type="password" id="password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                required />
        </div>
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>

    

<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right  ">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Numero titre
                </th>
                <th scope="col" class="px-6 py-3">
                    numero de point
                </th>
                <th scope="col" class="px-6 py-3">
                    nombre du bornes
                </th>
                <th scope="col" class="px-6 py-3">
                    numero du borne x
                </th>
                <th scope="col" class="px-6 py-3">
                    numero du borne y
                </th>
                <th scope="col" class="px-6 py-3">
                    Type du culture
                </th>
                <th scope="col" class="px-6 py-3">
                    type de ...
                </th>
                <th scope="col" class="px-6 py-3">
                    ... specifique
                </th>
                <th scope="col" class="px-6 py-3">
                    irrigation est ...
                </th>
                <th scope="col" class="px-6 py-3">
                    surface
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b ">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                    Apple MacBook Pro 17"
                </th>
                <td class="px-6 py-4">
                    Silver
                </td>
                <td class="px-6 py-4">
                    Laptop
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
                <td class="px-6 py-4">
                    $2999
                </td>
            </tr>
            
        </tbody>
    </table>
</div>

</body>