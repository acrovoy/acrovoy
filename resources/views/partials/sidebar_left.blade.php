<div class="border-right" style="width: 200px; min-height: 100vh; background-color:rgb(255, 255, 255);">
       
        <div class="list-group list-group-flush">
            <a href="/dashboard" class="list-group-item list-group-item-action" style="background-color:rgb(255, 255, 255)">Home</a>

                  
            @if(auth()->user()->manager)
            <a href="/add_product" class="list-group-item list-group-item-action" style="background-color:rgb(255, 255, 255)">Add Product +</a>
         @endif
            <a href="{{ route('profile') }}" class="list-group-item list-group-item-action" style="background-color:rgb(255, 255, 255)">Profile Information</a>
        
           
        </div>
    </div>