<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="p-8">
    <h1 class="text-3xl font-bold text-blue-600">Testing Fixed Tailwind</h1>

    <!-- Test Tailwind classes -->
    <div class="mt-4 p-4 bg-gray-100 rounded">
        <p class="text-lg">Tailwind base classes:</p>
        <div class="mt-2 grid grid-cols-3 gap-4">
            <div class="p-3 bg-red-500 text-white rounded">Red</div>
            <div class="p-3 bg-green-500 text-white rounded">Green</div>
            <div class="p-3 bg-blue-500 text-white rounded">Blue</div>
        </div>
    </div>

    <!-- Test custom buttons -->
    <div class="mt-6">
        <p class="text-lg mb-2">Custom buttons:</p>
        <button class="btn-primary">Primary</button>
        <button class="btn-danger ml-2">Danger</button>
        <button class="btn-secondary ml-2">Secondary</button>
    </div>

    <!-- Test if primary colors work -->
    <div class="mt-6">
        <p class="text-lg mb-2">Custom primary colors:</p>
        <div class="flex space-x-2">
            <div class="w-20 h-20 bg-primary-50 border"></div>
            <div class="w-20 h-20 bg-primary-500 border"></div>
            <div class="w-20 h-20 bg-primary-700 border"></div>
        </div>
    </div>
</body>
</html>
