<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Test
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{url('test-create')}}" method="post">
                        @csrf
                        <div>
                            <input type="text" class="@error('title') red-border @enderror" name="title" placeholder="Title" value="{{old('title')}}">
                            @error('title')
                            <p>{{$message}}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="text" name="description" class="@error('description') red-border @enderror" placeholder="Description" value="{{old('description')}}">
                            @error('description')
                            <p>{{$message}}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="text" name="asdf" class="" placeholder="Asdf">
                        </div>
                        <button class="btn btn-success" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
