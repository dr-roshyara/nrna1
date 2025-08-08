<div class="flex items-start">
    <div class="flex-shrink-0 mt-1">
        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
        </svg>
    </div>
    <div class="ml-3">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Authenticated User</p>
        <p class="text-sm font-semibold text-gray-900">
            {{ authUser?.name }} 
            <span class="text-xs text-gray-500 font-normal">(ID: {{ authUser?.id }})</span>
        </p>
        <!-- User Email with Icon -->
        <div class="flex items-center mt-1">
            <svg class="w-3 h-3 text-gray-400 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
            </svg>
            <span class="text-xs text-gray-600">{{ authUser?.email }}</span>
        </div>
    </div>
</div>