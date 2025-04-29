<x-mail-layout.mail-app>
    <div class="max-w-2xl mx-auto my-8 bg-white rounded-lg shadow-sm">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Your Email Title Here</h1>
        </div>
        
        <!-- Content -->
        <div class="px-6 py-6">
            <p class="text-gray-700 mb-4">
                Hello there! This is a simple email template built with HTML and Tailwind CSS. 
                You can customize this text to suit your needs.
            </p>
            
            <p class="text-gray-700 mb-6">
                Below is a link you might find interesting:
            </p>
            
            <a href="https://example.com" 
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
               style="text-decoration: none;">
                Click Here
            </a>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 text-center text-gray-500 text-sm rounded-b-lg">
            <p>© 2023 Your Company. All rights reserved.</p>
            <p class="mt-1">
                <a href="#" class="text-blue-500 hover:underline">Unsubscribe</a> | 
                <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a>
            </p>
        </div>
    </div>
</x-mail-layout.mail-app>